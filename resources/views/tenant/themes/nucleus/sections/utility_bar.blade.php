<div {!! $section->attributes() !!} 
    class="arz-utility-bar {{ $section->visibility }}" 
    style="
        padding-top: {{ $section->padding_y ?? 12 }}px; 
        padding-bottom: {{ $section->padding_y ?? 12 }}px;
        border-top: {{ $section->border_top ?? 1 }}px solid rgba(156, 163, 175, 0.2);
    ">
    
    <div class="{{ $section->container === 'full' ? 'w-full px-4' : 'container' }} flex flex-col md:flex-row items-center gap-4
        {{ $section->justify === 'left' ? 'justify-start' : ($section->justify === 'center' ? 'justify-center' : 'justify-between') }}">
        
        {!! $section->blocks() !!}
        
    </div>
</div>

<style>
    .arz-utility-bar .s-component {
        margin: 0; /* Override default block margins for utility bar */
    }
    
    /* Make menus display horizontally in utility bar */
    .arz-utility-bar .menu-block ul {
        display: flex;
        flex-direction: row;
        gap: 16px;
    }
    
    .arz-utility-bar .social-icons-wrapper {
        display: flex;
        gap: 12px;
    }
</style>
