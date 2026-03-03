<div class="logo-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('logo-settings-menu', 'arrow-logo')" type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-jelly fa-regular fa-image mr-1"></i> Logo & Favicon</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-logo"></i>
    </button>

    <div id="logo-settings-menu" class="max-h-0 overflow-hidden transition-all duration-300">

        <div class="grid grid-cols-1 gap-6 p-4 border-top">

            {{-- LOGO --}}
            <div class="grid grid-cols-12 gap-4 items-start">
                <div class="col-span-5 pt-2">
                    <div class="text-xs text-primary font-semibold">Main Logo</div>
                    <p class="text-[11px] text-tertiary mt-1">
                        Main logo shown in the header and branding areas of your site.
                    </p>
                </div>
                <div class="col-span-7">
                    <x-input.image name="logo" :value="$customizes['logo'] ?? null" icon="fa-image"
                        aspect="aspect-video" />
                </div>
            </div>


            {{-- INVERT LOGO --}}
            <div class="grid grid-cols-12 gap-4 items-start">
                <div class="col-span-5 pt-2">
                    <div class="text-xs text-primary font-semibold">Invert Logo</div>
                    <p class="text-[11px] text-tertiary mt-1">
                        Alternate logo for dark backgrounds or transparent header.
                    </p>
                </div>
                <div class="col-span-7">
                    <x-input.image name="invert_logo" :value="$customizes['invert_logo'] ?? null" icon="fa-adjust"
                        aspect="aspect-video" />
                </div>
            </div>


            {{-- FAVICON --}}
            <div class="grid grid-cols-12 gap-4 items-start">
                <div class="col-span-5 pt-2">
                    <div class="text-xs text-primary font-semibold">Favicon</div>
                    <p class="text-[11px] text-tertiary mt-1">
                        Small icon shown in browser tabs and bookmarks.
                    </p>
                </div>
                <div class="col-span-7">
                    <x-input.image name="favicon" :value="$customizes['favicon'] ?? null" icon="fa-star"
                        aspect="aspect-video" />
                </div>
            </div>

            <div class="border-top py-4">
                <h4 class="text-sm font-semibold text-primary">Logo Size Settings</h4>
            </div>

            {{-- LOGO --}}
            <div class="grid grid-cols-12 gap-4 items-start">
                <div class="col-span-5 pt-1 text-xs font-semibold text-primary">Destop Logo</div>
                <div class="col-span-7">
                    <x-input.range name="logo_height_desktop" :value="$customizes['logo_height_desktop'] ?? 40" min="20"
                        max="200" />
                </div>
            </div>

            {{-- LOGO --}}
            <div class="grid grid-cols-12 gap-4 items-start">
                <div class="col-span-5 pt-1 text-xs font-semibold text-primary">Mobile Logo</div>
                <div class="col-span-7">
                    <x-input.range name="logo_height_mobile" :value="$customizes['logo_height_mobile'] ?? 40" min="20"
                        max="150" />
                </div>
            </div>

        </div>
    </div>
</div>