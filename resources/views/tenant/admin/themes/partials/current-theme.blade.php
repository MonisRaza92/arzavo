{{-- CURRENT THEME BLOCK --}}
<div class="my-4 border-rounded border-primary bg-primary p-4">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        {{-- LEFT: THEME INFO --}}
        <div class="flex items-start gap-5">

            {{-- PREVIEW PLACEHOLDER --}}
            <div
                class="h-20 aspect-video border-rounded border-primary bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                Preview
            </div>

            {{-- DETAILS --}}
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">
                    Current Theme
                </p>

                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $activeTheme->theme_name }}
                </h2>

                @if($activeTheme)
                    <p class="text-sm text-gray-500 mt-1">
                        Applied on:
                        <span class="text-gray-700">
                            {{ $activeTheme->installed_at?->format('d M Y, h:i A') }}
                        </span>
                    </p>
                @endif
            </div>
        </div>

        {{-- RIGHT: ACTION --}}
        <div class="flex items-center gap-3">
            <a data-turbo="false"
                href="{{ route('admin.builder.index', ['theme' => $activeTheme->theme_slug, 'status' => $activeTheme->status, 'theme_id' => $activeTheme->id, 'is_active' => $activeTheme->is_active, 'page' => 'home']) }}"
                data-loading class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium rounded-md
                      bg-black text-white hover:bg-gray-800 transition">
                <i class="fa-solid fa-pen-to-square text-xs"></i>
                Customize Theme
            </a>
        </div>

    </div>
</div>
{{-- END CURRENT THEME BLOCK --}}