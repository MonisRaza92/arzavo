<!-- COLOR SCHEME ADD MODAL -->
<div id="colorSchemeModal"
    class="fixed bg-primary top-0 left-14 w-75 h-full pt-16 overflow-auto scrollbar z-40 hidden">

    <form id="colorSchemeForm"
          action="{{ route('admin.scheme.store') }}"
          method="POST">
        @csrf

        <input type="hidden" name="theme_id" value="{{ $theme->id }}">

        <div id="schemeFields">

            @foreach (config('color_schemes.color_schemes') as $schemeKey => $items)

                <h3
                    class="font-semibold text-primary p-4 border-bottom {{ $schemeKey === 'scheme_colors' ? '' : 'border-top' }}">
                    {{ ucwords(str_replace('_', ' ', $schemeKey)) }}
                </h3>

                <div class="p-4 space-y-4">

                    @foreach ($items as $item)
                        <div class="flex items-center justify-between gap-4">

                            <label class="text-xs w-1/2">
                                {{ $item['label'] }}
                            </label>

                            <x-input.color
                                :name="'colors[0]['.$schemeKey.']['.$item['key'].']'"
                                :value="$item['default'] ?? ''"
                                :gradient="($item['type'] ?? 'color') === 'gradient'"
                            />

                        </div>
                    @endforeach

                </div>

            @endforeach

            {{-- FOOTER --}}
            <div class="flex sticky bottom-0 bg-primary border-top items-center mt-6">
                <button type="button"
                        class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold"
                        onclick="closeColorSchemeModal()">
                    Close <i class="fa-solid fa-xmark"></i>
                </button>

                <button type="submit"
                        id="saveColorSchemeBtn"
                        class="bg-invert text-invert flex-1 text-center py-3 uppercase font-semibold">
                    Save <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>

        </div>
    </form>
</div>
