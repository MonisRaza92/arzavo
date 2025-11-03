<div class="faqs-section mt-15">
    <div class="container">
        <div class="section-heading mb-4">
            <h2 class="text-3xl font-bold mb-3 uppercase" style="color:var(--text-primary);">Frequently Asked Questions</h2>
            <p style="color:var(--secondary-text-color);">Find answers to the most common questions about our courses.</p>
        </div>
        <div class="faqs-list space-y-4">
            <!-- FAQ Item -->
            <div class="faq-item rounded-md p-3 pb-2" style="border: 1px solid var(--text-color);">
                <h3 class="faq-question font-semibold text-lg cursor-pointer relative" style="color: var(--text-color);">What is the duration of the courses? <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2"></i></h3>
                <p class="faq-answer mt-2 overflow-hidden max-h-0 opacity-0" style="color: var(--secondary-text-color);">
                    Each course varies in duration, typically ranging from 4 to 12 weeks.
                </p>
            </div>
            <!-- FAQ Item -->
            <div class="faq-item rounded-md p-3 pb-2" style="border: 1px solid var(--text-color);">
                <h3 class="faq-question font-semibold text-lg cursor-pointer relative" style="color: var(--text-color);">What is the duration of the courses? <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2"></i></h3>
                <p class="faq-answer mt-2 overflow-hidden max-h-0 opacity-0" style="color: var(--secondary-text-color);">
                    Each course varies in duration, typically ranging from 4 to 12 weeks.
                </p>
            </div>
            <!-- FAQ Item -->
            <div class="faq-item rounded-md p-3 pb-2" style="border: 1px solid var(--text-color);">
                <h3 class="faq-question font-semibold text-lg cursor-pointer relative" style="color: var(--text-color);">What is the duration of the courses? <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2"></i></h3>
                <p class="faq-answer mt-2 overflow-hidden max-h-0 opacity-0" style="color: var(--secondary-text-color);">
                    Each course varies in duration, typically ranging from 4 to 12 weeks.
                </p>
            </div>
            <!-- FAQ Item -->
            <div class="faq-item rounded-md p-3 pb-2" style="border: 1px solid var(--text-color);">
                <h3 class="faq-question font-semibold text-lg cursor-pointer relative" style="color: var(--text-color);">What is the duration of the courses? <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2"></i></h3>
                <p class="faq-answer mt-2 overflow-hidden max-h-0 opacity-0" style="color: var(--secondary-text-color);">
                    Each course varies in duration, typically ranging from 4 to 12 weeks.
                </p>
            </div>
            <!-- FAQ Item -->
            <div class="faq-item rounded-md p-3 pb-2" style="border: 1px solid var(--text-color);">
                <h3 class="faq-question font-semibold text-lg cursor-pointer relative" style="color: var(--text-color);">What is the duration of the courses? <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 transform -translate-y-1/2"></i></h3>
                <p class="faq-answer mt-2 overflow-hidden max-h-0 opacity-0" style="color: var(--secondary-text-color);">
                    Each course varies in duration, typically ranging from 4 to 12 weeks.
                </p>
            </div>
        </div>

    </div>
</div>
<style>
    .faq-answer {
        transition: all 0.4s ease-in-out;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const faqs = document.querySelectorAll(".faq-question");
        faqs.forEach(q => {
            q.addEventListener("click", () => {
                const answer = q.nextElementSibling;
                answer.classList.toggle("max-h-0");
                answer.classList.toggle("max-h-96");
                answer.classList.toggle("opacity-0");
                answer.classList.toggle("opacity-100");
            });
        });
    });
</script>