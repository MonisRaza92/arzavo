@foreach ($block->children->where('is_active', true)->whereNotNull('parent_block_id')->sortBy('order') as $child)
@include('tenant.themes.' . $theme->theme_slug . '.blocks.' . $child->type, ['block' => $child])
@endforeach