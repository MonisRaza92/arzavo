<div class="advanced-settings bg-primary border-bottom">
    <button onclick="openCustomizesMenu('advanced-settings-menu','arrow-advanced')" type="button" class="p-4 flex justify-between items-center w-full text-md font-semibold">
        <span><i class="fa-solid fa-code text-sm"></i> Advanced</span>
        <i class="fas fa-angle-right transition-all duration-300" id="arrow-advanced"></i>
    </button>
    <div id="advanced-settings-menu" class="overflow-hidden max-h-0">
        <div class="grid grid-cols-1 gap-4 p-4 border-top">
            @php
            $advancedSettings = [
            ['key'=>'custom_css','label'=>'Custom CSS','type'=>'textarea','value'=>''],
            ['key'=>'custom_js','label'=>'Custom JS','type'=>'textarea','value'=>''],
            ['key'=>'disable_right_click','label'=>'Disable Right Click','type'=>'radio','options'=>['enable','disable'],'value'=>'disable'],
            ];
            @endphp

            @foreach($advancedSettings as $item)
            <div class="flex flex-col gap-2 mb-2">
                <label class="text-primary text-xs">{{ $item['label'] }}</label>
                @if($item['type']==='textarea')
                <textarea name="{{ $item['key'] }}" rows="3" class="w-full p-2 border border-primary rounded">{{ $customizes[$item['key']] ?? $item['value'] }}</textarea>
                @elseif($item['type']==='radio')
                <div class="flex items-center gap-2">
                    @foreach($item['options'] as $option)
                    <label class="mr-2 text-xs flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="{{ $item['key'] }}" value="{{ $option }}" {{ ($customizes[$item['key']] ?? $item['value'])===$option?'checked':'' }} class="hidden peer">
                        <span class="px-2 py-1 border border-primary rounded-md peer-checked:bg-primary peer-checked:text-white">{{ ucfirst($option) }}</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>