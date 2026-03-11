@props([
'name' => '',
'value' => '',
'gradient' => false,
])

@php
$id = 'cp_' . Str::random(8);
@endphp

<div class="relative" data-color-picker id="{{ $id }}" data-gradient="{{ $gradient ? 1 : 0 }}">
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">

    {{-- Trigger --}}
    <div class="group flex items-center gap-2 cursor-pointer" data-trigger>
        <div class="w-10 h-8.5 border-rounded border-primary" data-preview></div>
        <div class="flex-1 hidden">
            <input type="text"
                data-input
                class="w-full text-xs font-medium p-2 border-rounded border-primary"
                placeholder="#000000">
        </div>
    </div>

    {{-- Popup --}}
    <div class="fixed left-65 shadow-2xl top-20 max-h-10/12 scrollbar overflow-y-auto z-50 hidden bg-primary" data-popup>
        <div class="w-75 border-rounded border-primary overflow-hidden">

            {{-- Header with Big Preview --}}
            <div class="relative h-32 border-bottom">
                <div class="absolute inset-0 bg-[linear-gradient(45deg,#f0f0f0_25%,transparent_25%,transparent_75%,#f0f0f0_75%,#f0f0f0),linear-gradient(45deg,#f0f0f0_25%,transparent_25%,transparent_75%,#f0f0f0_75%,#f0f0f0)] bg-[length:20px_20px] bg-[0_0,10px_10px]"></div>
                <div class="absolute inset-0" data-preview></div>
            </div>

            <div class="p-4 space-y-4">
                {{-- Mode Toggle --}}
                @if($gradient)
                <div class="flex items-center justify-between">
                    <div class="flex bg-tertiary border-rounded p-1 w-full" data-toggle>
                        <button type="button" data-mode="solid"
                            class="px-5 py-2 text-sm font-medium border-rounded flex-1 transition-all duration-200 bg-white shadow-sm">
                            Solid
                        </button>
                        <button type="button" data-mode="gradient"
                            class="px-5 py-2 text-sm font-medium border-rounded flex-1 transition-all duration-200">
                            Gradient
                        </button>
                    </div>
                </div>
                @endif

                {{-- SOLID MODE --}}
                <div data-solid>
                    {{-- Color Picker --}}
                    <div>
                        <label class="text-sm font-semibold text-secondary">Pick Color</label>
                        <div class="relative mt-1">
                            <input type="color"
                                class="w-full h-16 border-rounded border-primary cursor-pointer"
                                data-solid-picker>
                        </div>
                    </div>
                    {{-- OPACITY --}}
                    <div class="mt-3">
                        <label class="text-sm font-semibold text-secondary">
                           Opacity
                       </label>
                    
                       <div class="flex items-center gap-3 mt-1">
                           <input
                               type="range"
                               min="0"
                               max="100"
                               value="100"
                               class="w-full"
                                data-opacity>
                    
                            <span
                                class="text-xs w-10 text-right"
                                data-opacity-value>
                                100%
                            </span>
                        </div>
                    </div>

                </div>

                {{-- GRADIENT MODE --}}
                <div class="hidden space-y-4" data-gradient-ui>

                    {{-- Direction Selector --}}
                    <div>
                        <label class="text-sm font-semibold text-secondary">Direction</label>
                        <div class="flex gap-2 mt-2">
                            {{-- Visual Wheel --}}
                            <div class="relative w-18 h-18 rounded-full bg-linear-to-br from-gray-50 to-gray-100 border-primary flex items-center justify-center shadow-inner"
                                data-angle-wheel>
                                <div class="absolute w-1 h-8 bg-black rounded-full origin-bottom mb-4 transform -translate-y-2"
                                    data-angle-indicator></div>
                                <span class="text-base font-bold text-secondary w-full bg-tertiary border-rounded p-0.75 mt-2.5 text-center z-10 absolute top-full" data-angle-display>135°</span>
                            </div>

                            {{-- Quick Angles --}}
                            <div class="flex-1 grid grid-cols-3 gap-2">
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="0" class="quick-angle-btn">→</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="45" class="quick-angle-btn">↗</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="90" class="quick-angle-btn">↑</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="135" class="quick-angle-btn">↖</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="180" class="quick-angle-btn">←</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="225" class="quick-angle-btn">↙</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="270" class="quick-angle-btn">↓</button>
                                <button type="button" class="bg-tertiary p-1 border-rounded" data-quick-angle="315" class="quick-angle-btn">↘</button>
                            </div>
                        </div>
                    </div>

                    {{-- Gradient Preview Bar with Interactive Stops --}}
                    <div class="hidden">
                        <label class="text-sm font-semibold text-secondary">Gradient Preview</label>
                        <div class="relative h-16 border-rounded border-primary mt-1 overflow-hidden shadow-inner">
                            <div class="absolute inset-0 bg-[linear-gradient(45deg,#f0f0f0_25%,transparent_25%,transparent_75%,#f0f0f0_75%,#f0f0f0),linear-gradient(45deg,#f0f0f0_25%,transparent_25%,transparent_75%,#f0f0f0_75%,#f0f0f0)] bg-[length:10px_10px] bg-[0_0,5px_5px]"></div>
                            <div class="absolute inset-0" data-gradient-bar></div>

                            {{-- Interactive Stop Markers --}}
                            <div class="absolute inset-x-0 bottom-0 h-full" data-stop-markers>
                                <!-- Stop markers will be rendered here -->
                            </div>
                        </div>
                    </div>

                    {{-- Gradient Stops --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-secondary">Colors</label>
                            <button type="button"
                                class="px-4 py-2 text-sm font-medium border-rounded bg-tertiary hover:bg-indigo-50 transition-all"
                                data-add-stop>
                                + Add Color
                            </button>
                        </div>

                        <div class="space-y-2 custom-scrollbar" data-stops>
                            <!-- Stops will be rendered here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>