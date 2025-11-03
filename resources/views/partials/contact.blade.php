<div class="contact-section mt-15">
    <div class="container">
        <div class="contact flex gap-4 flex-col lg:flex-row overflow-x-hidden">
            <div class="contact-content lg:w-1/2 w-full">
                <h2 class="text-3xl font-bold mb-4 uppercase" style="color: var(--primary-color);">Get in Touch</h2>
                <p class="mb-6">If you have any questions or need further information, feel free to reach out to us. We are here to help!</p>
                <div class="contact-info flex gap-6 items-center rounded-md p-4 shadow-md mb-4" style="background-color: var(--secondary-background);">
                    <i class="fa-solid fa-envelope text-3xl" style="color: var(--primary-color);"></i>
                    <p class="mb-1 text-xl"><strong>Email:</strong> <a href="mailto:{{ $settings['email'] ?? 'arzaqinsights@gmail.com' }}" class="text-blue-500 hover:underline">{{ $settings['email'] ?? 'arzaqinsights@gmail.com' }}</a></p>
                </div>
                <div class="contact-info flex gap-6 items-center rounded-md p-4 shadow-md mb-4" style="background-color: var(--secondary-background);">
                    <i class="fa-solid fa-phone text-3xl" style="color: var(--primary-color);"></i>
                    <p class="mb-1 text-xl"><strong>Phone:</strong> <a href="tel:+91{{ $settings['phone'] ?? '9198599490' }}" class="text-blue-500 hover:underline">{{ $settings['phone'] ?? '9198599490' }}</a></p>
                </div>
                <div class="contact-info flex gap-6 items-center rounded-md p-4 shadow-md mb-4" style="background-color: var(--secondary-background);">
                    <i class="fa-solid fa-map-marker-alt text-3xl" style="color: var(--primary-color);"></i>
                    <p class="mb-1 text-xl"><strong>Address:</strong> {{ $settings['address'] ?? 'Block D, New Ashok Nagar, Delhi, 110096' }}</p>
                </div>
                <div class="contact-info flex justify-between items-center rounded-md p-8 md:p-4 shadow-md mb-4 " style="background-color: var(--secondary-background);">
                    <p class="mb-1 hidden md:block"> <i class="fa-solid fa-share-alt text-3xl" style="color: var(--primary-color);"></i> <strong class="text-xl ml-4">Follow us:</strong></p>
                    <ul class="flex justify-between items-center w-full md:w-auto md:gap-6">
                        <li><a href="#" class="text-2xl p-2.5 rounded-md" style="color: var(--text-light); background-color: var(--primary-color);"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="#" class="text-2xl p-2.5 rounded-md" style="color: var(--text-light); background-color: var(--primary-color);"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a href="#" class="text-2xl p-2.5 rounded-md" style="color: var(--text-light); background-color: var(--primary-color);"><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a href="#" class="text-2xl p-2.5 rounded-md" style="color: var(--text-light); background-color: var(--primary-color);"><i class="fa-brands fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="contact-form lg:w-1/2 w-full overflow-hidden">
                <x-contact />
            </div>
        </div>
    </div>
</div>