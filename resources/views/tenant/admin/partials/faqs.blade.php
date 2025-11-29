<div class="faq-section my-6">
    <h2 class="text-xl font-semibold text-primary mb-2">Frequently Asked Questions</h2>
    <!-- Add FAQ Form -->
    <div class="add-faq-card border-rounded bg-primary border-primary p-4 mb-6">
        <h3 class="text-lg font-semibold mb-3">Add New FAQ</h3>

        <form action="{{ route('admin-add-faq') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="faqQuestion" class="block text-sm font-medium mb-1">Question</label>
                <input type="text" id="faqQuestion" name="question" placeholder="Enter your question"
                    class="w-full border text-sm p-2 border-primary border-rounded" required>
            </div>

            <div>
                <label for="faqAnswer" class="block text-sm font-medium mb-1">Answer</label>
                <textarea id="faqAnswer" name="answer" rows="3" placeholder="Enter the answer"
                    class="w-full border text-sm p-2 border-primary border-rounded" required></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="text-sm px-4 py-2 border-rounded bg-invert text-invert">
                    <i class="fa-solid fa-plus mr-1"></i> Add FAQ
                </button>
            </div>
        </form>
    </div>

    <!-- List of Existing FAQs -->
    <div class="existing-faqs space-y-4">
        <h3 class="text-xl text-primary font-semibold mb-2">All FAQs</h3>

        @if ($faqs->count() > 0)
        @foreach ($faqs as $faq)
        <div class="faq-item border-rounded border-primary bg-primary p-4 relative">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-semibold text-md text-primary">{{ $faq->question }}</h4>
                    <p class="text-sm text-secondary mt-1">{{ $faq->answer }}</p>
                </div>
                <form action="{{ route('admin-delete-faq', ['id' => $faq->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-primary text-sm px-2 py-1 border-rounded hover:bg-gray-200">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
        @else
        <p class="text-sm text-tertiary">No FAQs added yet.</p>
        @endif
    </div>
</div>