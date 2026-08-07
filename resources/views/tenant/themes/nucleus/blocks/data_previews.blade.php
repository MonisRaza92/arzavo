@php
    $s = $block['settings'] ?? [];

    $showHeading = filter_var($s['show_heading'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $heading = $s['heading'] ?? 'Sample Page Previews';
    $headingType = $s['heading_type'] ?? 'h6';

    $cols = (int) ($s['columns'] ?? 4);
    $cols = max(1, min(10, $cols));
    $aspect = $s['aspect_ratio'] ?? 'portrait';
    $radius = (int) ($s['border_radius'] ?? 8);

    $enableLimit = filter_var($s['enable_limit'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $displayLimit = (int) ($s['display_limit'] ?? 4);
    $displayLimit = max(1, $displayLimit);

    $aspectClass = match($aspect) {
        'square' => 'aspect-square',
        'video' => 'aspect-video',
        default => 'aspect-[3/4]',
    };

    $previews = isset($data) && method_exists($data, 'previews') ? $data->previews : collect([]);
    $totalCount = $previews->count();
    $extraCount = ($enableLimit && $totalCount > $displayLimit) ? ($totalCount - $displayLimit) : 0;

    $previewDataList = [];
    foreach ($previews as $idx => $prv) {
        $previewDataList[] = [
            'src' => media($prv->file_path),
            'title' => $prv->title ?? ''
        ];
    }
@endphp

<div {!! $block->attributes() !!} class="w-full space-y-3" style="{{ $block->margin }}" x-data="{ expanded: false }">

    @if($showHeading && filled($heading))
        <div class="arz-{{ $headingType }} font-bold text-primary flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span>{!! $heading !!}</span>
            </div>
            @if($enableLimit && $extraCount > 0)
                <button type="button" @click="expanded = !expanded" class="text-xs font-semibold text-accent hover:underline flex items-center gap-1">
                    <span x-text="expanded ? 'Hide Extra' : 'Show All ({{ $totalCount }})'"></span>
                    <i class="fa-solid" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
            @endif
        </div>
    @endif

    @if($totalCount > 0)
        <div class="grid gap-2.5" style="grid-template-columns: repeat({{ $cols }}, minmax(0, 1fr));">
            @foreach($previews as $idx => $prv)
                @php
                    $isOverLimit = ($enableLimit && $idx >= $displayLimit);
                    $isLimitCard = ($enableLimit && $idx === ($displayLimit - 1) && $extraCount > 0);
                @endphp

                <div class="group relative overflow-hidden bg-secondary border border-primary cursor-pointer transition-all hover:shadow-lg arz-border"
                     style="border-radius: {{ $radius }}px;"
                     @if($isOverLimit) x-show="expanded" x-cloak @endif
                     @click="{{ $isLimitCard ? 'if(!expanded) { expanded = true; } else { openPreviewLightbox(' . $idx . '); }' : 'openPreviewLightbox(' . $idx . ')' }}">
                    
                    <div class="w-full {{ $aspectClass }} overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                        <img src="{{ media($prv->file_path) }}" alt="{{ $prv->title ?? 'Preview Image' }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                    </div>

                    @if(filled($prv->title))
                        <div class="p-1.5 text-xs text-primary bg-primary border-t border-primary truncate font-medium text-center">
                            {{ $prv->title }}
                        </div>
                    @endif

                    @if($isLimitCard)
                        {{-- +More Overlay Badge on the Limit Card when collapsed --}}
                        <div x-show="!expanded" class="absolute inset-0 bg-black/75 flex flex-col items-center justify-center text-white transition-opacity z-10 p-2 text-center backdrop-blur-xs">
                            <span class="text-2xl font-extrabold">+{{ $extraCount + 1 }}</span>
                            <span class="text-[11px] font-semibold tracking-wide uppercase mt-0.5 opacity-90">More Pages</span>
                            <i class="fa-solid fa-images text-sm mt-1 opacity-80"></i>
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white" @if($isLimitCard) x-show="expanded" @endif>
                        <i class="fa-solid fa-magnifying-glass-plus text-lg"></i>
                    </div>
                </div>
            @endforeach
        </div>

        @if($enableLimit && $extraCount > 0)
            <div class="flex justify-center pt-1">
                <button type="button" @click="expanded = !expanded" class="px-4 py-1.5 bg-secondary border border-primary text-xs font-semibold text-primary rounded-full hover:bg-primary transition-all flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid" :class="expanded ? 'fa-chevron-up' : 'fa-layer-group'"></i>
                    <span x-text="expanded ? 'Hide Extra Previews' : 'Show All {{ $totalCount }} Previews (+{{ $extraCount + 1 }} more)'"></span>
                </button>
            </div>
        @endif
    @endif
</div>

{{-- Fullscreen Lightbox Modal --}}
@once
<div id="previewLightboxModal" class="fixed inset-0 z-[99999] bg-black/90 hidden flex flex-col items-center justify-between p-4 md:p-6 transition-opacity backdrop-blur-sm select-none" onclick="closePreviewLightbox()">
    
    {{-- Header Bar --}}
    <div class="w-full flex justify-between items-center text-white max-w-6xl z-10" onclick="event.stopPropagation()">
        <span id="previewLightboxCounter" class="text-sm font-semibold bg-white/10 px-3 py-1 rounded-full border border-white/20">1 / 1</span>
        <button type="button" onclick="closePreviewLightbox()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-xl transition-all border border-white/20">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    {{-- Main Image Area with Prev/Next Navigation --}}
    <div class="relative w-full max-w-5xl flex-1 flex items-center justify-center my-2 overflow-hidden" onclick="event.stopPropagation()">
        {{-- Previous Button --}}
        <button type="button" onclick="prevPreviewLightboxSlide()" class="absolute left-2 z-20 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center text-xl transition-all border border-white/20 shadow-lg">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        {{-- Lightbox Image --}}
        <img id="previewLightboxImage" src="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl transition-all duration-300">

        {{-- Next Button --}}
        <button type="button" onclick="nextPreviewLightboxSlide()" class="absolute right-2 z-20 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center text-xl transition-all border border-white/20 shadow-lg">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    {{-- Caption Footer --}}
    <div class="w-full text-center max-w-2xl z-10" onclick="event.stopPropagation()">
        <p id="previewLightboxCaption" class="text-white text-base font-semibold drop-shadow-md min-h-[1.5rem]"></p>
    </div>
</div>

<script>
    let globalPreviewList = @json($previewDataList);
    let currentPreviewIdx = 0;

    function openPreviewLightbox(index) {
        if (!globalPreviewList || !globalPreviewList.length) return;
        currentPreviewIdx = index;
        updatePreviewLightboxContent();
        const modal = document.getElementById('previewLightboxModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function updatePreviewLightboxContent() {
        if (!globalPreviewList || !globalPreviewList[currentPreviewIdx]) return;
        const item = globalPreviewList[currentPreviewIdx];
        const img = document.getElementById('previewLightboxImage');
        const cap = document.getElementById('previewLightboxCaption');
        const count = document.getElementById('previewLightboxCounter');
        if (img) img.src = item.src;
        if (cap) cap.textContent = item.title || '';
        if (count) count.textContent = (currentPreviewIdx + 1) + ' / ' + globalPreviewList.length;
    }

    function prevPreviewLightboxSlide() {
        if (!globalPreviewList.length) return;
        if (currentPreviewIdx > 0) {
            currentPreviewIdx--;
        } else {
            currentPreviewIdx = globalPreviewList.length - 1;
        }
        updatePreviewLightboxContent();
    }

    function nextPreviewLightboxSlide() {
        if (!globalPreviewList.length) return;
        if (currentPreviewIdx < globalPreviewList.length - 1) {
            currentPreviewIdx++;
        } else {
            currentPreviewIdx = 0;
        }
        updatePreviewLightboxContent();
    }

    function closePreviewLightbox() {
        const modal = document.getElementById('previewLightboxModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('previewLightboxModal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closePreviewLightbox();
            if (e.key === 'ArrowLeft') prevPreviewLightboxSlide();
            if (e.key === 'ArrowRight') nextPreviewLightboxSlide();
        }
    });
</script>
@endonce
