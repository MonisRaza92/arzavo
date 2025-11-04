@extends('layouts.editor')
@section('title', 'Webpage Builder')
@section('content')
@include('admin.builder.includes.navbar')
@include('admin.builder.includes.sidebar')
<div class="p-4 ml-[300px] mt-16" id="previeweSection">
    <div class="builder-container relative">
        <div class="w-full relative border-rounded overflow-auto scrollbar" style="{{ $customizes['primary-color'] ?? '#f5f5f5' }}">
            @include('admin.builder.includes.preview')
        </div>
    </div>
</div>
<x-image-upload />
<script>
    function openCustomizesMenu(menuId, arrowId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);
        if (menu.classList.contains('max-h-0')) {
            menu.classList.remove('max-h-0');
            arrow.classList.add('rotate-90');
        } else {
            menu.classList.add('max-h-0');
            arrow.classList.remove('rotate-90');
        }
    }

    function selectCustomizeOption(key, value) {
        // Reset all options for that key
        document.querySelectorAll(`[onclick*="selectCustomizeOption('${key}'"]`).forEach(el => {
            el.classList.remove('ring-2', 'ring-primary');
        });

        // Highlight selected
        const selected = event.currentTarget;
        selected.classList.add('ring-2', 'ring-primary');
        document.getElementById(key).value = value;
    }
</script>
@endsection