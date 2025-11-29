@foreach ($block->children->where('is_active', true)->whereNotNull('parent_block_id')->sortBy('order') as $child)
@include('tenant.website.blocks.' . $child->type, ['block' => $child])
@endforeach