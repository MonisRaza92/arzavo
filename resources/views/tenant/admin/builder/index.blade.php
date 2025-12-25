@extends('layouts.editor')
@section('title', 'Webpage Builder - ' . ($theme->theme_name ?? 'Nucleus') . ' - ' . app('currentTenant')->name)
@section('content')
@include('tenant.admin.builder.includes.navbar')
@include('tenant.admin.builder.includes.sidebar')
<div class="p-4 ml-[300px] mt-16" id="previeweSection">
    <div class="builder-container relative">
        <div class="w-full relative border-rounded overflow-auto scrollbar" style="{{ $customizes['primary-color'] ?? '#f5f5f5' }}">
            @include('tenant.admin.builder.includes.preview')
        </div>
    </div>
</div>
<x-image-upload />
<script>
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