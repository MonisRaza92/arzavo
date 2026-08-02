<div class="checkout-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('checkout-settings-menu','arrow-checkout')"
        type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span><i class="fa-solid fa-credit-card mr-1"></i> Checkout & Success</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-checkout"></i>
    </button>

    <div id="checkout-settings-menu" class="overflow-hidden max-h-0 transition-all duration-300">

        {{-- CATEGORY 1: CHECKOUT PAGE --}}
        <div class="category-section">
            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">
                Checkout Page
            </h3>
            
            <div class="grid grid-cols-1 gap-4 p-4">
                {{-- Logo --}}
                <div class="grid grid-cols-12 gap-4 items-start">
                    <div class="col-span-5 pt-2">
                        <div class="text-xs text-primary font-semibold">Checkout Logo</div>
                        <p class="text-[11px] text-tertiary mt-1">
                            Custom logo for checkout pages.
                        </p>
                    </div>
                    <div class="col-span-7">
                        <x-input.image name="checkout_logo" :value="$customizes['checkout_logo'] ?? null" icon="fa-image" aspect="aspect-video" />
                    </div>
                </div>

                {{-- Logo Width --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Logo Width</label>
                    <div class="flex items-center border-rounded border-primary">
                        <div class="flex items-center w-39 gap-2 px-2 py-3">
                            <input type="range"
                                name="checkout_logo_width"
                                min="50"
                                max="350"
                                step="5"
                                value="{{ $customizes['checkout_logo_width'] ?? 150 }}"
                                class="w-full accent-black cursor-pointer"
                                oninput="this.nextElementSibling.innerText = this.value + 'px'">
                            <span class="text-xs text-primary min-w-10 text-right font-semibold">
                                {{ $customizes['checkout_logo_width'] ?? 150 }}px
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Logo Alignment --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Logo Alignment</label>
                    <div class="flex items-center border-rounded border-primary">
                        <div class="flex w-39">
                            @foreach(['left', 'center', 'right'] as $align)
                            <label class="cursor-pointer flex-1 w-full flex">
                                <input type="radio"
                                    id="checkout_logo_align_{{ $align }}"
                                    name="checkout_logo_align"
                                    value="{{ $align }}"
                                    {{ ($customizes['checkout_logo_align'] ?? 'left') === $align ? 'checked' : '' }}
                                    class="hidden peer">
                                <span class="py-2 flex-1 text-sm text-center border-rounded inline-block peer-checked:bg-black peer-checked:text-white transition-all duration-200">
                                    {{ ucfirst($align) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Header Background --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Header Background</label>
                    <x-input.color name="checkout_header_bg" :value="$customizes['checkout_header_bg'] ?? '#ffffff'" />
                </div>

                {{-- Main Form Area BG --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Main Form Area BG</label>
                    <x-input.color name="checkout_form_bg" :value="$customizes['checkout_form_bg'] ?? '#ffffff'" />
                </div>

                {{-- Order Summary BG --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Order Summary BG</label>
                    <x-input.color name="checkout_summary_bg" :value="$customizes['checkout_summary_bg'] ?? '#fafafa'" />
                </div>

                {{-- Button BG (Primary Color) --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Button BG / Accent</label>
                    <x-input.color name="checkout_primary_color" :value="$customizes['checkout_primary_color'] ?? '#4f46e5'" />
                </div>

                {{-- Button Text Color --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Button Text Color</label>
                    <x-input.color name="checkout_btn_text_color" :value="$customizes['checkout_btn_text_color'] ?? '#ffffff'" />
                </div>

                {{-- Page Text Color --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Body Text Color</label>
                    <x-input.color name="checkout_text_color" :value="$customizes['checkout_text_color'] ?? '#111827'" />
                </div>

                {{-- Input Border Color --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Input Border Color</label>
                    <x-input.color name="checkout_input_border_color" :value="$customizes['checkout_input_border_color'] ?? '#e5e7eb'" />
                </div>

                {{-- Input Background --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Input Background</label>
                    <x-input.color name="checkout_input_bg" :value="$customizes['checkout_input_bg'] ?? '#ffffff'" />
                </div>

                {{-- Input Text Color --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Input Text Color</label>
                    <x-input.color name="checkout_input_text_color" :value="$customizes['checkout_input_text_color'] ?? '#111827'" />
                </div>

                {{-- Border Radius --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Corner Radius</label>
                    <div class="flex items-center border-rounded border-primary">
                        <div class="flex items-center w-39 gap-2 px-2 py-3">
                            <input type="range"
                                name="checkout_border_radius"
                                min="0"
                                max="40"
                                step="1"
                                value="{{ $customizes['checkout_border_radius'] ?? 12 }}"
                                class="w-full accent-black cursor-pointer"
                                oninput="this.nextElementSibling.innerText = this.value + 'px'">
                            <span class="text-xs text-primary min-w-10 text-right font-semibold">
                                {{ $customizes['checkout_border_radius'] ?? 12 }}px
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CATEGORY 2: SUCCESS PAGE --}}
        <div class="category-section">
            <h3 class="text-sm font-semibold text-primary px-4 py-3 border-top border-bottom">
                Success Page
            </h3>
            
            <div class="grid grid-cols-1 gap-4 p-4">
                {{-- Success Icon Color --}}
                <div class="flex items-center justify-between">
                    <label class="text-primary text-xs">Success Icon Color</label>
                    <x-input.color name="checkout_success_icon_color" :value="$customizes['checkout_success_icon_color'] ?? '#16a34a'" />
                </div>

                {{-- Success Subtitle --}}
                <div class="flex flex-col gap-2">
                    <label class="text-primary text-xs">Success Subtitle</label>
                    <div class="flex items-center border-rounded border-primary w-full bg-white">
                        <input type="text"
                            name="checkout_success_subtitle"
                            value="{{ $customizes['checkout_success_subtitle'] ?? 'Thank you for your order. Your order number is' }}"
                            class="w-full p-2.5 border-none outline-none text-sm text-primary bg-transparent" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
