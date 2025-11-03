@extends('layouts.admin')
@section('title', 'Images Library')
@section('content')
<div class="bg-primary border-rounded border-primary">
    <div class="flex justify-between items-center p-4">
        <h3 class="text-primary text-lg font-bold"><i class="fa-solid fa-images"></i> Images Library</h3>
        <form id="image-upload-form" action="{{ route('admin-upload-image') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="imageInput" class="bg-invert text-md text-invert py-2 px-3 border-rounded">Upload File <i class="fas fa-upload pl-2 border-left"></i></label>
            <input type="file" name="image" accept="image/*" class="hidden" id="imageInput" onchange="submitImagesForm()">
        </form>
    </div>
    <div class="images border-top overflow-x-auto">
        <table class="w-full">
            <thead class="bg-tertiary text-primary">
                <tr>
                    <th class="p-2 pl-4 text-left">Preview</th>
                    <th class="p-2 text-left hidden md:block">Filename</th>
                    <th class="p-2 text-left">Uploaded at</th>
                    <th class="p-2 text-left">File Size</th>
                    <th class="p-2 text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($images as $image)
                @php
                $filepath = public_path($image->filepath);
                $filesize = file_exists($filepath) ? filesize($filepath) : 0;
                $size = $filesize ? number_format($filesize / (1024 * 1024), 2) . ' MB' : '-';
                @endphp
                <tr class="hover:bg-gray-50 border-top">
                    <td class="p-2 pl-4">
                        <a href="{{ asset($image->filepath) }}" target="_blank">
                            <img src="{{ asset($image->filepath) }}" alt="{{ $image->filename }}" class="w-16 object-cover border-rounded">
                        </a>
                    </td>
                    <td class="p-2 hidden md:block">{{ $image->filename }}</td>
                    <td class="p-2">{{ $image->created_at->format('d-m-Y') }}</td>
                    <td class="p-2">{{ $size }}</td>
                    <td class="p-2">
                        <form action="{{ route('admin-delete-image', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xl text-tertiary"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    function submitImagesForm() {
        document.getElementById('image-upload-form').submit();
    }
</script>
@endsection