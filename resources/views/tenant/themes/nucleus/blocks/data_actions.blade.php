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
    $previewPath = $data->preview_file_path ?? $data->file_path ?? null;

    // Paid check: same as data_pricing
    $isPaid    = ($data->price_type ?? '') === 'paid' || ($data->is_paid ?? false) == true || (($data->price ?? 0) > 0);
    $salePrice = $data->sale_price ?? $data->discount_price ?? null;
    $price     = $data->price ?? null;

    // Build access gate URLs — slug auto-extracted from Referer by controller
    $downloadUrl = $itemId ? route('item.download', ['type' => $itemType]) : null;
    $readUrl     = $itemId ? route('item.read',     ['type' => $itemType]) : null;

    $containerClasses   = ['flex', 'items-center', 'gap-3', 'w-full'];
    $containerClasses[] = $singleLine ? 'flex-nowrap' : 'flex-wrap';

    $btnClasses = ['inline-flex', 'items-center', 'justify-center', 'gap-2'];
    if ($buttonWidth === 'full') {
        $btnClasses[] = $singleLine ? 'flex-1 w-full text-center' : 'w-full text-center';
    }
@endphp

<div {!! $block->attributes() !!} class="{{ implode(' ', $containerClasses) }}" style="{{ $block->margin }}">

    {{-- 💰 Price / Free Badge --}}
    <!-- @if($isPaid)
        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-300">
            <i class="fa-solid fa-tag text-[10px]"></i>
            @if($salePrice && $salePrice > 0 && $salePrice != $price)
                ₹{{ number_format($salePrice, 2) }}
                <span class="line-through opacity-60 font-normal">₹{{ number_format($price, 2) }}</span>
            @elseif($price)
                ₹{{ number_format($price, 2) }}
            @endif
        </span>
    @else
        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700 border border-green-300">
            <i class="fa-solid fa-unlock text-[10px]"></i>
            Free
        </span>
    @endif -->

    {{-- 📥 Download Button --}}
    @if($showDownload && $filePath && $downloadUrl)
        <a href="{{ $downloadUrl }}" class="arz-btn-primary {{ implode(' ', $btnClasses) }}">
            <i class="fa-solid fa-download"></i>
            <span>{{ $downloadLabel }}</span>
        </a>
    @endif

    {{-- 📖 Read Online Button --}}
    @if($showPreview && $previewPath && $readUrl)
        <a href="{{ $readUrl }}" class="arz-btn-secondary {{ implode(' ', $btnClasses) }}">
            <i class="fa-solid fa-book-reader"></i>
            <span>{{ $previewLabel }}</span>
        </a>
    @endif

</div>

