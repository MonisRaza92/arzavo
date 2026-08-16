@php
    $s = $block['settings'] ?? [];

    $downloadLabel = $s['download_label'] ?? 'Download PDF';
    $previewLabel  = $s['preview_label']  ?? 'Read Online';

    $showDownload = filter_var($s['show_download'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $showPreview  = filter_var($s['show_preview']  ?? true, FILTER_VALIDATE_BOOLEAN);

    $buttonWidth = $s['button_width'] ?? 'auto';
    $singleLine  = filter_var($s['single_line'] ?? false, FILTER_VALIDATE_BOOLEAN);

    // Resolve item type (book / course / future)
    $itemType = 'book';
    if ($data instanceof \App\Models\Tenant\Course) {
        $itemType = 'course';
    }
    $itemId = $data->id ?? null;

    $filePath    = $data->file_path ?? null;
    $previewPath = $data->preview_file_path ?? null;

    // Price and Paid check
    $currentPrice = (float) ($data->sale_price ?? $data->discount_price ?? $data->price ?? 0);
    $isPaid = $currentPrice > 0 || ($data->price_type ?? '') === 'paid' || ($data->is_paid ?? false) == true;

    // Ownership check for logged in tenant user
    $user = auth('tenant')->user();
    $isOwned = false;

    if ($user && $itemId) {
        $morphType = ($itemType === 'course') ? 'App\Models\Tenant\Course' : 'App\Models\Tenant\Book';
        
        $isOwned = \App\Models\Tenant\UserEntitlement::where('user_id', $user->id)
            ->where('entitable_id', $itemId)
            ->where(function($q) use ($morphType, $itemType) {
                $q->where('entitable_type', $morphType)
                  ->orWhere('entitable_type', $itemType)
                  ->orWhere('entitable_type', strtolower(class_basename($morphType)));
            })
            ->exists();

        if (!$isOwned && $itemType === 'course') {
            try {
                $isOwned = \Illuminate\Support\Facades\DB::connection('tenant')
                    ->table('course_enrollments')
                    ->where('user_id', $user->id)
                    ->where('course_id', $itemId)
                    ->where('status', 'active')
                    ->exists();
            } catch (\Throwable $e) {}
        }

        if (!$isOwned) {
            $isOwned = \App\Models\Tenant\OrderItem::whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('payment_status', 'paid');
            })->where('purchasable_id', $itemId)->exists();
        }
    }

    // URLs
    $checkoutUrl = $itemId ? route('checkout.show', ['purchasable_type' => $itemType, 'purchasable_id' => $itemId]) : '#';
    $downloadUrl = $itemId ? route('item.download', ['type' => $itemType, 'id' => $itemId, 'slug' => $data->slug ?? '']) : null;
    $readUrl     = $itemId ? route('item.read',     ['type' => $itemType, 'id' => $itemId, 'slug' => $data->slug ?? '']) : null;

    $containerClasses   = ['flex', 'items-center', 'gap-3', 'w-full'];
    $containerClasses[] = $singleLine ? 'flex-nowrap' : 'flex-wrap';

    $btnClasses = ['inline-flex', 'items-center', 'justify-center', 'gap-2'];
    if ($buttonWidth === 'full') {
        $btnClasses[] = $singleLine ? 'flex-1 w-full text-center' : 'w-full text-center';
    }
@endphp

<div {!! $block->attributes() !!} class="{{ implode(' ', $containerClasses) }}" style="{{ $block->margin }}">

    {{-- CASE 1: PAID AND NOT OWNED => SHOW BUY / CHECKOUT BUTTON --}}
    @if($isPaid && !$isOwned)
        @if($showDownload && $itemId)
            <a href="{{ $checkoutUrl }}" class="arz-btn-primary {{ implode(' ', $btnClasses) }}">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Buy Now @if($currentPrice > 0) • ₹{{ number_format($currentPrice, 2) }} @endif</span>
            </a>
        @endif

        @if($showPreview && ($previewPath || $filePath) && $readUrl)
            <a href="{{ $readUrl }}" class="arz-btn-secondary {{ implode(' ', $btnClasses) }}">
                <i class="fa-solid fa-book-open"></i>
                <span>{{ $previewPath ? 'Read Sample' : $previewLabel }}</span>
            </a>
        @endif

    {{-- CASE 2: FREE OR ALREADY PURCHASED => DIRECT DOWNLOAD & READ FULL ACCESS --}}
    @else
        @if($showDownload && $filePath && $downloadUrl)
            <a href="{{ $downloadUrl }}" class="arz-btn-primary {{ implode(' ', $btnClasses) }}">
                <i class="fa-solid fa-download"></i>
                <span>{{ $downloadLabel }}</span>
            </a>
        @endif

        @if($showPreview && ($previewPath || $filePath) && $readUrl)
            <a href="{{ $readUrl }}" class="arz-btn-secondary {{ implode(' ', $btnClasses) }}">
                <i class="fa-solid fa-book-reader"></i>
                <span>{{ $previewLabel }}</span>
            </a>
        @endif
    @endif

</div>
