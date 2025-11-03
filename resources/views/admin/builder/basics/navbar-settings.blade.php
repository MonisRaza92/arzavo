<div class="navbar-settings bg-primary border-rounded border-primary mb-4">
    <button onclick="openCustomizesMenu('navbar-settings-menu', 'arrow-navbar')" type="button"
        class="p-4 flex justify-between items-center w-full text-lg font-bold uppercase">
        <span><i class="fa-solid fa-bars"></i> Navbar Settings</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-navbar"></i>
    </button>

    <div id="navbar-settings-menu" class="max-h-0 overflow-hidden transition-all duration-300">
        <div class="navbar-settings border-top p-4 pb-8">
            {{-- ===== NAVBAR STYLE ===== --}}
            <div class="mb-6">
                <h4 class="text-tertiary font-semibold mb-3 uppercase">Navbar Style</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Navbar Size --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Navbar Size</label>
                        <div class="flex gap-3">
                            @foreach (['compact' => 'Compact', 'standard' => 'Standard'] as $value => $label)
                            <div class="flex-1 cursor-pointer text-center border-primary border-rounded bg-secondary p-2 {{ ($customizes['navbar_size'] ?? 'standard') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('navbar_size', '{{ $value }}')">
                                <div class="w-full h-6 {{ $value === 'compact' ? 'bg-tertiary h-4' : 'bg-tertiary h-8' }} border-rounded mx-auto mb-1"></div>
                                <span class="text-primary text-sm font-semibold">{{ $label }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="navbar_size" id="navbar_size" value="{{ $customizes['navbar_size'] ?? 'standard' }}">
                        </div>
                    </div>

                    {{-- Navbar Type --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Navbar Type</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['fixed' => 'Fixed', 'scroll' => 'Scroll Up', 'floating' => 'Floating'] as $value => $label)
                            <div class="cursor-pointer text-center border-primary border-rounded bg-secondary {{ $value === 'fixed' || $value === 'scroll' ? 'p-0' : 'p-2' }} {{ ($customizes['navbar_type'] ?? 'fixed') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('navbar_type', '{{ $value }}')">
                                <div class="w-full h-6 bg-tertiary border-rounded mb-1"></div>
                                <span class="text-primary text-xs font-semibold block">{{ $label }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="navbar_type" id="navbar_type" value="{{ $customizes['navbar_type'] ?? 'fixed' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== POSITION SETTINGS ===== --}}
            <div class="mb-6">
                <h4 class="text-tertiary font-semibold mb-3 uppercase">Position & Alignment</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Logo Position --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Logo Position</label>
                        <div class="flex gap-3 justify-between">
                            @foreach (['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $value => $label)
                            <div class="flex-1 text-center cursor-pointer border-primary border-rounded bg-secondary p-2 {{ ($customizes['logo_position'] ?? 'left') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('logo_position', '{{ $value }}')">
                                <div class="flex items-center px-1 {{ $value === 'left' ? 'justify-start' : ($value === 'center' ? 'justify-center' : 'justify-end') }} bg-tertiary h-5 rounded">
                                    <div class="w-4 h-2 bg-invert border-rounded"></div>
                                </div>
                                <span class="text-primary text-xs font-semibold">{{ $label }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="logo_position" id="logo_position" value="{{ $customizes['logo_position'] ?? 'left' }}">
                        </div>
                    </div>

                    {{-- Nav Links Position --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Nav Links Position</label>
                        <div class="flex gap-3 justify-between">
                            @foreach (['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $value => $label)
                            <div class="flex-1 text-center cursor-pointer border-primary border-rounded bg-secondary p-2 {{ ($customizes['navlinks_position'] ?? 'center') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('navlinks_position', '{{ $value }}')">
                                <div class="flex items-center px-1 {{ $value === 'left' ? 'justify-start' : ($value === 'center' ? 'justify-center' : 'justify-end') }} bg-tertiary h-5 rounded">
                                    <div class="w-12 h-1 bg-invert border-rounded"></div>
                                </div>
                                <span class="text-primary text-xs font-semibold">{{ $label }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="navlinks_position" id="navlinks_position" value="{{ $customizes['navlinks_position'] ?? 'center' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== VISUAL STYLING ===== --}}
            <div class="mb-6">
                <h4 class="text-tertiary font-semibold mb-3 uppercase">Visual Styling</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Logo Size --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Logo Size</label>
                        <div class="flex gap-3 justify-between">
                            @foreach ([['40', 'Small'], ['50', 'Medium'], ['60', 'Large']] as $size)
                            <div class="flex-1 text-center cursor-pointer border-primary border-rounded bg-secondary p-2 {{ ($customizes['logo_size'] ?? '50px') === $size[0] ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('logo_size', '{{ $size[0] }}')">
                                <div class="mx-auto bg-tertiary border-rounded aspect-video" style="height: {{ str_replace('px','',$size[0])/2 }}px;"></div>
                                <span class="text-primary text-xs font-semibold mt-1 block">{{ $size[1] }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="logo_size" id="logo_size" value="{{ $customizes['logo_size'] ?? '50px' }}">
                        </div>
                    </div>

                    {{-- Nav Link Style --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Nav Link Style</label>
                        <div class="flex gap-3 justify-between">
                            @foreach (['bold' => 'Bold', 'thin' => 'Thin'] as $value => $label)
                            <div class="flex-1 cursor-pointer text-center bg-secondary border-primary border-rounded p-2 {{ ($customizes['navlink_style'] ?? 'bold') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('navlink_style', '{{ $value }}')">
                                @if ($value === 'bold')
                                <div class="flex justify-center gap-1 items-center border-bottom pb-2">
                                    <div class="bg-invert text-invert border-rounded px-2 py-1 text-xs font-bold">Link</div>
                                    <div class="bg-tertiary text-primary border-rounded px-2 py-1 text-xs font-semibold">Link</div>
                                    <div class="bg-tertiary text-primary border-rounded px-2 py-1 text-xs font-semibold">Link</div>
                                    <div class="bg-tertiary text-primary border-rounded px-2 py-1 text-xs font-semibold">Link</div>
                                </div>
                                @else
                                <div class="flex justify-center gap-3 items-center border-bottom pb-2">
                                    <div class="text-primary text-xs font-bold">Link</div>
                                    <div class="text-tertiary text-xs font-semibold">Link</div>
                                    <div class="text-tertiary text-xs font-semibold">Link</div>
                                    <div class="text-tertiary text-xs font-semibold">Link</div>
                                </div>
                                @endif
                                <span class="text-primary text-xs font-semibold mt-1 block">{{ $label }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="navlink_style" id="navlink_style" value="{{ $customizes['navlink_style'] ?? 'bold' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== MOBILE MENU SETTINGS ===== --}}
            <div>
                <h4 class="text-tertiary font-semibold mb-3 uppercase">Mobile Menu Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Menu Button Position --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Menu Button Position</label>
                        <div class="flex gap-3 justify-between">
                            @foreach (['left' => 'Left', 'right' => 'Right'] as $value => $label)
                            <div class="flex-1 text-center cursor-pointer border-primary border-rounded bg-secondary p-2 {{ ($customizes['mobile_btn_position'] ?? 'right') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('mobile_btn_position', '{{ $value }}')">
                                <div class="flex items-center px-1 {{ $value === 'left' ? 'justify-start' : ($value === 'center' ? 'justify-center' : 'justify-end') }} bg-tertiary h-5 rounded">
                                    <i class="fa-solid fa-bars text-primary"></i>
                                </div>
                                <span class="text-primary text-xs font-semibold">{{ $label }}</span>
                            </div>
                            @endforeach
                            <input type="hidden" name="mobile_btn_position" id="mobile_btn_position" value="{{ $customizes['mobile_btn_position'] ?? 'right' }}">
                        </div>
                    </div>

                    {{-- Mobile Menu Style --}}
                    <div class="flex flex-col border-primary border-rounded p-3">
                        <label class="font-semibold text-primary mb-2">Bottom Menu</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['none' => 'None', 'bottom_fixed' => 'Bottom Fixed', 'bottom_floating' => 'Bottom Floating'] as $value => $label)
                            <div class="cursor-pointer text-center border-primary border-rounded bg-secondary {{ $value === 'bottom_floating' ? 'p-2' : 'p-0' }} {{ ($customizes['mobile_menu_style'] ?? 'slide') === $value ? 'ring-2 ring-primary' : '' }}"
                                onclick="selectCustomizeOption('mobile_menu_style', '{{ $value }}')">
                                <span class="text-primary text-xs font-semibold">{{ $label }}</span>
                                @if ($value === 'none')
                                <div class="mx-auto"></div>
                                @elseif($value === 'bottom_fixed')
                                <div class="bg-tertiary h-6 mt-2 w-full"></div>
                                @else
                                <div class="bg-tertiary h-4 w-full border-rounded"></div>
                                @endif
                            </div>
                            @endforeach
                            <input type="hidden" name="mobile_menu_style" id="mobile_menu_style" value="{{ $customizes['mobile_menu_style'] ?? 'slide' }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Save Button -->
        <div class="flex justify-end p-4 border-top">
            <button type="submit" class="btn bg-invert text-invert font-bold px-6 py-2 border-rounded">
                Save Navbar Settings
            </button>
        </div>
    </div>
</div>