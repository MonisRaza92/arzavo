@foreach ($section->blocks->where('is_active', true)->whereNull('parent_block_id')->sortBy('order') as $block)
@include('tenant.website.blocks.' . $block->type, ['block' => $block])
@endforeach