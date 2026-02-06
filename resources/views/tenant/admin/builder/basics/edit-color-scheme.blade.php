<!-- COLOR SCHEME MODAL -->
@foreach($colorSchemes as $scheme)
    <div id="colorSchemeEditModal{{ $scheme->id }}"
        class="fixed bg-primary top-0 left-14 pt-16 w-75 overflow-y-auto h-full scrollbar z-40 hidden">
        <form id="colorSchemeEditForm{{ $scheme->id }}" class="color-scheme-form"
            action="{{ route('admin.scheme.update', $scheme->id) }}">
            @csrf
            @method('PUT')
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

                                <x-input.color :name="'colors[0][' . $schemeKey . '][' . $item['key'] . ']'"
                                    :gradient="($item['type'] ?? 'color') === 'gradient'"
                                    value="{{ $scheme->$schemeKey->{$item['key']} }}" />

                            </div>
                        @endforeach

                    </div>

                @endforeach
                <div class="flex sticky bottom-0 bg-primary border-top items-center">
                    <button type="button" class="bg-primary text-primary flex-1 text-center py-3 uppercase font-semibold"
                        onclick="window.activeColorSchemeId = null; document.getElementById('colorSchemeEditModal{{ $scheme->id }}').classList.add('hidden');">
                        Close <i class="fa-solid fa-xmark"></i>
                    </button>

                    <button type="button" class="bg-invert text-invert flex-1 text-center py-3 uppercase font-semibold"
                        onclick="submitColorScheme({{ $scheme->id }})">
                        <span id="updateColorSchemeText{{ $scheme->id }}">Save</span>
                        <i class="fa-solid fa-floppy-disk"></i>
                    </button>

                </div>
            </div>
        </form>
    </div>
@endforeach