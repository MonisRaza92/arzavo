<div class="colors-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('colors-settings-menu', 'arrow-colors')" type="button"
        class="p-4 flex justify-between items-center w-full text-sm font-semibold bg-hover-secondary">
        <span>Colors</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-colors"></i>
    </button>

    <div class="colors-settings-menu overflow-hidden max-h-0" id="colors-settings-menu">
        <div class="info p-4 border-top pb-0">
            <h3 class="text-sm font-semibold text-primary">Saved Schemes</h3>
            <p class="text-xs text-secondary mt-1">Color schemes will be applied to sections throughout your website.</p>
        </div>
        <div class="grid grid-cols-3 gap-2 p-4">

            @foreach($colorSchemes as $scheme)

            @php
            $bg = $scheme->scheme_colors->background;
            $heading = $scheme->scheme_colors->heading;
            $paragraph = $scheme->scheme_colors->paragraph;
            $border = $scheme->scheme_colors->border;

            $primaryBtnBg = $scheme->primary_btn->background;
            $primaryBtnText = $scheme->primary_btn->text;
            $primaryBtnBorder = $scheme->primary_btn->border;

            $secondaryBtnBg = $scheme->secondary_btn->background;
            $secondaryBtnText = $scheme->secondary_btn->text;
            $secondaryBtnBorder = $scheme->secondary_btn->border;
            @endphp

            <div>
                <div class="h-16 p-2 border-primary border-rounded cursor-pointer flex flex-col justify-center items-center"
                    onclick="openColorSchemeEditModal('{{ $scheme->id }}')"
                    style="background: {{ $bg }}; border-color: {{ $border }};">

                    <div class="text-center">
                        <!-- Text Preview -->
                        <div class="flex items-center gap-1 -mb-1.5" style="color: {{ $heading }};">
                            <i class="fa-solid fa-font text-[10px]"></i>
                            <span class="text-xs font-semibold">Heading</span>
                        </div>
                        <span class="text-[10px]" style="color: {{ $paragraph }};">Paragraph</span>
                    </div>

                    <!-- Buttons Preview -->
                    <div class="flex gap-2 items-center mt-auto">
                        <div class="px-3.5 py-1 border-rounded border"
                            style="background: {{ $primaryBtnBg }}; border-color: {{ $primaryBtnBorder }};">
                        </div>

                        <div class="px-3.5 py-1 border-rounded border"
                            style="background: {{ $secondaryBtnBg }}; border-color: {{ $secondaryBtnBorder }};">
                        </div>
                    </div>
                </div>
                <p class="text-xs text-center mt-1">Scheme {{ $scheme->id }}</p>
            </div>

            @endforeach


            <!-- ADD NEW SCHEME CARD -->
            <div class="p-4 border border-primary h-16 border-rounded cursor-pointer flex flex-col justify-center items-center text-center transition-all hover:opacity-80"
                onclick="openColorSchemeModal()">

                <div class="text-2xl font-bold">+</div>
                <div class="text-[10px] font-semibold">Add New</div>
            </div>

        </div>
    </div>

</div>