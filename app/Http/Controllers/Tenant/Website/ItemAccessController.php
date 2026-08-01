<?php

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Book;
use App\Models\Tenant\Course;
use App\Models\Tenant\UserEntitlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemAccessController extends Controller
{
    /**
     * Resolve the purchasable model from type + slug.
     */
    protected function resolveItem(string $type, string $slug): ?object
    {
        return match (strtolower($type)) {
            'book'   => Book::where('slug', $slug)->first(),
            'course' => Course::where('slug', $slug)->first(),
            default  => null,
        };
    }

    /**
     * Extract slug from referer URL (?slug=xxx) or fall back to current request param.
     * Book page URL format: /book?slug=some-slug
     */
    protected function extractSlug(Request $request): string
    {
        // First try: slug from current request query param
        if ($request->filled('slug')) {
            return $request->query('slug');
        }

        // Second: extract ?slug= from HTTP Referer header
        $referer = $request->header('referer', '');
        if ($referer) {
            $parsed = parse_url($referer);
            parse_str($parsed['query'] ?? '', $params);
            if (!empty($params['slug'])) {
                return $params['slug'];
            }
            // Also handle path-based slug: /books/some-slug
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
        // Book uses price_type field: 'free' | 'paid'
        if (isset($item->price_type)) {
            return $item->price_type === 'paid';
        }
        // Course uses is_paid boolean
        if (isset($item->is_paid)) {
            return (bool) $item->is_paid;
        }
        // Fallback: if price > 0 then paid
        return (($item->price ?? 0) > 0);
    }

    /**
     * Check if current authenticated tenant user has an active entitlement.
     */
    protected function hasEntitlement(object $item, string $type): bool
    {
        $user = Auth::guard('tenant')->user();
        if (!$user) return false;

        $morphType = match (strtolower($type)) {
            'book'   => 'App\Models\Tenant\Book',
            'course' => 'App\Models\Tenant\Course',
            default  => null,
        };

        if (!$morphType) return false;

        return UserEntitlement::where('user_id', $user->id)
            ->where('entitable_type', $morphType)
            ->where('entitable_id', $item->id)
            ->exists();
    }

    /**
     * Access Gate: Download action.
     * GET /item/download?type=book
     * (slug is extracted from the Referer URL automatically)
     */
    public function download(Request $request)
    {
        $type = $request->query('type', 'book');
        $slug = $this->extractSlug($request);

        if (!$slug) abort(400, 'Could not determine which item to download.');

        $item = $this->resolveItem($type, $slug);
        if (!$item) abort(404, 'Item not found.');

        // Step 1: Must be logged in
        if (!Auth::guard('tenant')->check()) {
            session(['url.intended' => route('item.download', ['type' => $type, 'slug' => $slug])]);
            return redirect()->route('tenant.login')
                ->with('info', 'Please login or register to access this content.');
        }

        // Step 2: If paid => check entitlement
        if ($this->isPaid($item) && !$this->hasEntitlement($item, $type)) {
            return redirect()->route('checkout.show', [
                'purchasable_type' => $type,
                'purchasable_id'   => $item->id,
            ])->with('info', 'Please complete payment to access this item.');
        }

        // Step 3: Serve the file
        $filePath = $item->file_path ?? null;
        if (!$filePath) abort(404, 'File not available.');

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
     * GET /item/read?type=book
     * (slug is extracted from the Referer URL automatically)
     */
    public function read(Request $request)
    {
        $type = $request->query('type', 'book');
        $slug = $this->extractSlug($request);

        if (!$slug) abort(400, 'Could not determine which item to read.');

        $item = $this->resolveItem($type, $slug);
        if (!$item) abort(404, 'Item not found.');

        // Step 1: Must be logged in
        if (!Auth::guard('tenant')->check()) {
            session(['url.intended' => route('item.read', ['type' => $type, 'slug' => $slug])]);
            return redirect()->route('tenant.login')
                ->with('info', 'Please login or register to read this content.');
        }

        // Step 2: If paid => check entitlement
        if ($this->isPaid($item) && !$this->hasEntitlement($item, $type)) {
            return redirect()->route('checkout.show', [
                'purchasable_type' => $type,
                'purchasable_id'   => $item->id,
            ])->with('info', 'Please complete payment to read this item.');
        }

        // Step 3: Redirect to preview file (opens in browser/PDF viewer)
        $previewPath = $item->preview_file_path ?? $item->file_path ?? null;
        if (!$previewPath) abort(404, 'Preview file not available.');

        return redirect(media($previewPath));
    }
}
