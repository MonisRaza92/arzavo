<div {!! $section->attributes() !!} 
     class="{{ $section->visibility }} arz-border-t" 
     style="
         padding-top: {{ $section->padding_y ?? 12 }}px; 
         padding-bottom: {{ $section->padding_y ?? 12 }}px;
         border-top-width: {{ $section->border_top ?? 1 }}px;
     ">

    <div
        class="{{ $section->container }} flex flex-row items-center gap-4
        {{ $section->justify === 'left' ? 'justify-start' : ($section->justify === 'center' ? 'justify-center' : 'justify-between') }}">
        <p class="arz-body-text"> © {{ date('Y') }} {{ tenant_name() }}. All rights reserved. Powered By Arzavo</p>
        {!! $section->blocks() !!}
    </div>
</div>
