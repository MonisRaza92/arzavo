@props(['name', 'label' => '', 'value' => '', 'placeholder' => 'Paste link or select', 'class' => ''])

<x-input.wrapper :label="$label">
    <div class="relative url-picker">
        
        {{-- Left Link Icon --}}
        <span class="absolute left-2.5 text-tertiary text-xs pointer-events-none z-10" style="top: 50%; transform: translateY(-50%);">
            <i class="fa-solid fa-link"></i>
        </span>

        {{-- Input (Direct child of url-picker, satisfying JS input.parentElement) --}}
        <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
            autocomplete="off" class="w-full text-xs p-2 pl-7 pr-7 border-rounded border-primary bg-primary text-primary input-focus cursor-pointer {{ $class }}"
            onclick="openUrlPicker(this)">

        {{-- Right Chevron Icon --}}
        <span class="absolute right-2.5 text-tertiary text-[10px] pointer-events-none z-10" style="top: 50%; transform: translateY(-50%);">
            <i class="fa-solid fa-chevron-down"></i>
        </span>

        {{-- POPUP --}}
        <div class="url-popup hidden absolute right-0 top-full mt-1.5 w-64 bg-primary border-primary border-rounded shadow-xl z-50">

            {{-- HEADER --}}
            <div class="flex items-center gap-2 p-2 border-bottom">
                <button type="button" class="url-back shrink-0 hidden p-2 bg-hover-secondary border-rounded flex items-center justify-center text-xs text-secondary hover:text-primary transition-colors"
                    onclick="urlBack(this)">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <input type="text" placeholder="Search..."
                    class="url-search p-2 w-full text-xs bg-primary border-primary border-rounded input-focus text-primary">
            </div>

            {{-- CONTENT --}}
            <div class="max-h-64 overflow-auto url-content">

                {{-- GROUP LIST (DEFAULT SCREEN) --}}
                <div class="url-groups">
                    @foreach ($urls as $group => $items)
                        <button type="button"
                            class="url-group w-full text-left py-2.5 px-3 border-top text-xs text-secondary hover:text-primary hover:bg-hover-secondary flex justify-between items-center transition-colors"
                            data-group="{{ $group }}" onclick="openUrlGroup(this)">
                            <span class="font-medium">{{ $group }}</span>
                            <i class="fa-solid fa-chevron-right text-[9px] text-tertiary"></i>
                        </button>
                    @endforeach
                </div>

                {{-- LINKS SCREENS --}}
                @foreach ($urls as $group => $items)
                    <div class="url-links hidden" data-group="{{ $group }}">
                        @foreach ($items as $link)
                            <button type="button"
                                class="block w-full text-left py-2.5 px-3 border-top text-xs text-secondary hover:text-primary hover:bg-hover-secondary flex items-center gap-2 transition-colors"
                                onclick="selectUrl(this,'{{ $link['url'] }}')">
                                <i class="fa-solid fa-circle text-[6px] text-tertiary"></i>
                                <span class="truncate">{{ $link['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endforeach

            </div>
        </div>

    </div>
</x-input.wrapper>
