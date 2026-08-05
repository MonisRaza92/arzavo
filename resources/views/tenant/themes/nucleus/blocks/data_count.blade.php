@php
    $type = $block['type'] ?? $block->type ?? 'book_count';
@endphp
<div {!! $block->attributes() !!} class="bg-blue-500! rounded-full py-1 px-3">
    @if ($type === 'blog_count')
        {{ isset($data->blogs) ? $data->blogs->count() : (\App\Models\Tenant\Blog::published()->count()) }}
    @else
        {{ isset($data->books) ? $data->books->count() : 0 }}
    @endif
</div>