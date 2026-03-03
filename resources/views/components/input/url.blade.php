@props(['name', 'label' => '', 'value' => '', 'placeholder' => 'Paste link or select', 'class' => '', 'urls' => []])

<x-input.wrapper :label="$label">

    <div class="relative url-picker">

        <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
            autocomplete="off" class="w-full text-xs p-2 border-rounded border-primary bg-transparent {{ $class }}"
            onclick="openUrlPicker(this)">

        {{-- POPUP --}}
        <div
            class="url-popup hidden absolute right-0 top-full mt-2 w-60 bg-primary border-primary rounded shadow-lg z-50">

            {{-- HEADER --}}
            <div class="flex items-center gap-2 p-2 border-bottom">

                <button type="button" class="url-back shrink-0 hidden p-1.5 bg-hover-secondary border-rounded"
                    onclick="urlBack(this)">
                    <i class="fa-duotone fa-solid fa-arrow-left-from-bracket"></i>
                </button>

                <input type="text" placeholder="Search..."
                    class="url-search p-2 w-full text-xs border-primary rounded">

            </div>

            {{-- CONTENT --}}
            <div class="max-h-64 overflow-auto url-content">

                {{-- GROUP LIST (DEFAULT SCREEN) --}}
                <div class="url-groups">

                    @foreach ($urls as $group => $items)
                        <button type="button"
                            class="url-group w-full text-left p-3 border-top  text-sm hover:bg-hover-secondary"
                            data-group="{{ $group }}" onclick="openUrlGroup(this)">
                            {{ $group }}
                        </button>
                    @endforeach

                </div>

                {{-- LINKS SCREENS --}}
                @foreach ($urls as $group => $items)
                    <div class="url-links hidden" data-group="{{ $group }}">

                        @foreach ($items as $link)
                            <button type="button"
                                class="block w-full text-left p-3 border-top  text-sm hover:bg-hover-secondary"
                                onclick="selectUrl(this,'{{ $link['url'] }}')">
                                {{ $link['label'] }}
                            </button>
                        @endforeach

                    </div>
                @endforeach

            </div>

        </div>

    </div>

</x-input.wrapper>
