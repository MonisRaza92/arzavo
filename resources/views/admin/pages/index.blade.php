@extends('layouts.admin')
@section('title', 'Web Pages')
@section('content')
<div class="bg-primary border-rounded border-primary">
    <h3 class="text-primary text-lg font-bold px-4 py-3 flex justify-between items-center w-full"><span><i class="fa-solid fa-window-restore"></i> Web Pages</span> <button type="button" onclick="document.getElementById('page-create-form').classList.toggle('hidden')" class="py-2 px-3 bg-invert text-invert text-sm border-rounded font-normal">Add Page <i class="fas fa-plus px-2 border-left"></i></button></h3>
    <table class="w-full">
        <thead>
            <tr class="bg-tertiary">
                <th class="p-2 pl-4 text-left">Name</th>
                <th class="p-2 text-left">Slug (URL)</th>
                <th class="p-2 text-left">Visibility</th>
                <th class="p-2 text-center hidden md:block">Created at</th>
                <th class="p-2 text-right pr-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $page)
            <tr class="border-top">
                <td class="p-2 pl-4 text-left">{{ $page->name }}</td>
                <td class="p-2 text-left">/{{ $page->slug }}</td>
                <td class="p-2 text-left">
                    {{ $page->status ? 'Published' : 'Draft' }}
                </td>
                <td class="p-2 text-center hidden md:block">{{ $page->created_at->format('d-m-Y') }}</td>
                <td class="p-2 text-right pr-4">
                    <button type="button" class="text-xl text-tertiary" onclick="openEditModal({{ $page->id }}, '{{ $page->name }}', '{{ $page->slug }}', '{{ $page->meta_title }}', `{{ $page->meta_description }}`, '{{ $page->status }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline">
                        @csrf 
                        @method('DELETE')
                        <button class="text-xl text-tertiary" onclick="return confirm('Delete page?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.pages.forms.page-update-form')
@include('admin.pages.forms.page-create-form')
@endsection