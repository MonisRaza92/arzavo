@php
    $s = $block['settings'] ?? [];
    $title = $s['title'] ?? $block->title ?? 'Customer Reviews & Ratings';

    $reviews = (is_object($data) && method_exists($data, 'approvedReviews')) ? $data->approvedReviews()->latest()->get() : collect();
    $avg = (is_object($data) && method_exists($data, 'averageRating')) ? $data->averageRating() : 5.0;
    $count = (is_object($data) && method_exists($data, 'reviewsCount')) ? $data->reviewsCount() : 0;
    $breakdown = (is_object($data) && method_exists($data, 'ratingBreakdown')) ? $data->ratingBreakdown() : [];
@endphp

<div data-block-id="{{ $block['id'] }}" data-name="{{ $block['name'] }}" class="w-full bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-gray-100">
        <div>
            <h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-3xl font-extrabold text-gray-900">{{ number_format($avg, 1) }}</span>
                <div class="flex text-amber-400 text-sm">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= round($avg) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                    @endfor
                </div>
                <span class="text-sm font-semibold text-gray-500">Based on {{ $count }} reviews</span>
            </div>
        </div>

        <button type="button" onclick="document.getElementById('review-form-modal-{{ $block['id'] }}').classList.toggle('hidden')" 
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow transition-all">
            Write a Review
        </button>
    </div>

    {{-- STAR RATING BREAKDOWN BARS --}}
    @if(!empty($breakdown))
        <div class="max-w-md space-y-2">
            @foreach($breakdown as $star => $dataInfo)
                <div class="flex items-center text-xs text-gray-600 gap-3">
                    <span class="w-12 font-bold">{{ $star }} Stars</span>
                    <div class="flex-1 bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-amber-400 h-full rounded-full" style="width: {{ $dataInfo['percentage'] }}%;"></div>
                    </div>
                    <span class="w-10 text-right font-semibold">{{ $dataInfo['percentage'] }}%</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- WRITE REVIEW MODAL FORM --}}
    <div id="review-form-modal-{{ $block['id'] }}" class="hidden p-6 bg-gray-50 rounded-xl border border-gray-200 space-y-4">
        <h4 class="font-bold text-gray-900 text-md">Write Your Review</h4>
        
        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="reviewable_type" value="{{ is_object($data) ? get_class($data) : 'App\Models\Tenant\Book' }}">
            <input type="hidden" name="reviewable_id" value="{{ $data->id ?? 1 }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Your Name *</label>
                    <input type="text" name="author_name" required value="{{ auth()->user()?->name }}" class="w-full px-4 py-2 text-xs rounded-xl border outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Your Email *</label>
                    <input type="email" name="author_email" required value="{{ auth()->user()?->email }}" class="w-full px-4 py-2 text-xs rounded-xl border outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Rating Score *</label>
                <select name="rating" required class="px-4 py-2 text-xs rounded-xl border outline-none font-bold text-amber-600">
                    <option value="5">⭐⭐⭐⭐⭐ (5/5 Excellent)</option>
                    <option value="4">⭐⭐⭐⭐ (4/5 Very Good)</option>
                    <option value="3">⭐⭐⭐ (3/5 Average)</option>
                    <option value="2">⭐⭐ (2/5 Below Average)</option>
                    <option value="1">⭐ (1/5 Poor)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Review Comment *</label>
                <textarea name="comment" required rows="3" placeholder="Share details of your experience with this item..." class="w-full px-4 py-2 text-xs rounded-xl border outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Attach Photos (Optional)</label>
                <input type="file" name="review_media[]" multiple accept="image/*" class="text-xs text-gray-600">
            </div>

            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold text-xs rounded-xl shadow hover:bg-indigo-700">
                Submit Review
            </button>
        </form>
    </div>

    {{-- REVIEWS LIST --}}
    <div class="space-y-4 pt-4 divide-y divide-gray-100">
        @forelse($reviews as $rev)
            <div class="pt-4 space-y-2">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 text-sm">{{ $rev->author_name }}</span>
                            @if($rev->is_verified_buyer)
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-800 rounded-full flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check"></i> Verified Buyer
                                </span>
                            @endif
                        </div>
                        <div class="flex text-amber-400 text-xs mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $rev->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $rev->comment }}</p>

                @if($rev->media && $rev->media->count() > 0)
                    <div class="flex gap-2 pt-2">
                        @foreach($rev->media as $m)
                            <a href="{{ media($m->file_path) }}" target="_blank">
                                <img src="{{ media($m->file_path) }}" class="w-16 h-16 object-cover rounded-lg border">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500 py-4 text-center">No reviews yet. Be the first to write a review!</p>
        @endforelse
    </div>
</div>
