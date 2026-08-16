<?php

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Book;
use App\Models\Tenant\Course;
use App\Models\Tenant\UserEntitlement;
use App\Models\Tenant\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemAccessController extends Controller
{
    /**
     * Resolve the purchasable model from request (id or slug).
     */
    protected function resolveItem(Request $request, string $type): ?object
    {
        if ($request->filled('id')) {
            $id = $request->query('id');
            return match (strtolower($type)) {
                'book', 'books'     => Book::find($id),
                'course', 'courses' => Course::find($id),
                default             => Book::find($id),
            };
        }

        $slug = $this->extractSlug($request);
        if (!$slug) return null;

        return match (strtolower($type)) {
            'book', 'books'     => Book::where('slug', $slug)->first(),
            'course', 'courses' => Course::where('slug', $slug)->first(),
            default             => Book::where('slug', $slug)->first(),
        };
    }

    /**
     * Extract slug from referer URL (?slug=xxx) or fall back to current request param.
     * Book page URL format: /book?slug=some-slug
     */
    protected function extractSlug(Request $request): string
    {
        if ($request->filled('slug')) {
            return $request->query('slug');
        }

        $referer = $request->header('referer', '');
        if ($referer) {
            $parsed = parse_url($referer);
            parse_str($parsed['query'] ?? '', $params);
            if (!empty($params['slug'])) {
                return $params['slug'];
            }
            $pathSegments = explode('/', trim($parsed['path'] ?? '', '/'));
            $last = end($pathSegments);
            if ($last && $last !== 'book' && $last !== 'course') {
                return $last;
            }
        }

        return '';
    }

    /**
     * Determine if an item is paid (requires purchase).
     */
    protected function isPaid(object $item): bool
    {
        $price = (float) ($item->sale_price ?? $item->discount_price ?? $item->price ?? 0);
        if ($price > 0) {
            return true;
        }

        if (isset($item->price_type) && strtolower($item->price_type) === 'paid') {
            return true;
        }

        if (isset($item->is_paid) && (bool) $item->is_paid) {
            return true;
        }

        return false;
    }

    /**
     * Check if current authenticated tenant user has an active entitlement or paid order.
     */
    protected function hasEntitlement(object $item, string $type): bool
    {
        $user = Auth::guard('tenant')->user();
        if (!$user) return false;

        $morphType = match (strtolower($type)) {
            'book', 'books'     => 'App\Models\Tenant\Book',
            'course', 'courses' => 'App\Models\Tenant\Course',
            default             => 'App\Models\Tenant\Book',
        };

        // 1. Check UserEntitlements table
        $hasEntitlement = UserEntitlement::where('user_id', $user->id)
            ->where('entitable_id', $item->id)
            ->where(function ($q) use ($morphType, $type) {
                $q->where('entitable_type', $morphType)
                  ->orWhere('entitable_type', $type)
                  ->orWhere('entitable_type', class_basename($morphType))
                  ->orWhere('entitable_type', strtolower(class_basename($morphType)));
            })
            ->exists();

        if ($hasEntitlement) return true;

        // 2. Check Course Enrollment table
        if ($morphType === 'App\Models\Tenant\Course' || $item instanceof Course) {
            try {
                $isEnrolled = DB::connection('tenant')
                    ->table('course_enrollments')
                    ->where('user_id', $user->id)
                    ->where('course_id', $item->id)
                    ->where('status', 'active')
                    ->exists();
                if ($isEnrolled) return true;
            } catch (\Throwable $e) {}
        }

        // 3. Check Order items for paid orders
        return OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('payment_status', 'paid');
        })
        ->where('purchasable_id', $item->id)
        ->exists();
    }

    /**
     * Access Gate: Download action.
     * GET /item/download?type=book&id=xxx or ?slug=xxx
     */
    public function download(Request $request)
    {
        $type = $request->query('type', 'book');
        $item = $this->resolveItem($request, $type);

        if (!$item) {
            return redirect()->route('tenant.books')->with('error', 'Item not found.');
        }

        // Step 1: Must be logged in if paid
        $isPaid = $this->isPaid($item);

        if ($isPaid && !Auth::guard('tenant')->check()) {
            session(['url.intended' => route('checkout.show', ['purchasable_type' => $type, 'purchasable_id' => $item->id])]);
            return redirect()->route('checkout.show', [
                'purchasable_type' => $type,
                'purchasable_id'   => $item->id,
            ])->with('info', 'Please complete checkout to access this item.');
        }

        // Step 2: If paid => check entitlement. If not owned, redirect to checkout
        if ($isPaid && !$this->hasEntitlement($item, $type)) {
            return redirect()->route('checkout.show', [
                'purchasable_type' => $type,
                'purchasable_id'   => $item->id,
            ])->with('info', 'Please complete checkout to access this item.');
        }

        // Step 3: Serve the file
        $filePath = $item->file_path ?? null;
        if (!$filePath) {
            return back()->with('error', 'Download file is not available.');
        }

        // Increment download counter
        if (method_exists($item, 'increment')) {
            try { $item->increment('downloads_count'); } catch (\Exception $e) {}
        }

        if (Storage::disk('public')->exists($filePath)) {
            $fullPath = Storage::disk('public')->path($filePath);
            $fileName = $item->title ? Str::slug($item->title) . '.pdf' : basename($filePath);
            return response()->download($fullPath, $fileName);
        }

        // Fallback: redirect to media URL
        return redirect(media($filePath));
    }

    /**
     * Access Gate: Read / Stream Online action.
     * GET /item/read?type=book&id=xxx or ?slug=xxx
     */
    public function read(Request $request)
    {
        $type = $request->query('type', 'book');
        $item = $this->resolveItem($request, $type);

        if (!$item) {
            return redirect()->route('tenant.books')->with('error', 'Item not found.');
        }

        $isPaid = $this->isPaid($item);

        // If preview/sample file exists, allow reading sample without payment
        $previewPath = $item->preview_file_path ?? null;

        // If no preview path, they are trying to read the full file
        if (!$previewPath && $isPaid && !$this->hasEntitlement($item, $type)) {
            return redirect()->route('checkout.show', [
                'purchasable_type' => $type,
                'purchasable_id'   => $item->id,
            ])->with('info', 'Please complete checkout to read the full book.');
        }

        $targetPath = $previewPath ?: ($item->file_path ?? null);
        if (!$targetPath) {
            return back()->with('error', 'Reading preview is not available.');
        }

        return redirect(media($targetPath));
    }
}
