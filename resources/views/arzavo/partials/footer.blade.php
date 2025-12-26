<!-- Footer -->
<footer class="bg-invert text-invert-secondary py-20 relative overflow-hidden">

    <!-- Subtle Background Decoration -->
    <div class="absolute inset-0 hidden sm:block opacity-5">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-accent border-rounded rotate-12"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-accent-secondary border-rounded -rotate-12"></div>
    </div>

    <div class="container relative z-10">

        <!-- Top Footer -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">

            <!-- Brand -->
            <div class="space-y-6">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo/arzavo-white.png') }}" alt="Arzavo" class="h-16 shrink-0">
                </div>

                <p class="text-invert-secondary leading-relaxed">
                    Arzavo helps schools, colleges, and coaching institutes build their
                    <strong class="text-invert">own website and management system</strong>
                    — fully isolated, branded, and scalable.
                </p>

                <div class="flex space-x-3">
                    <a href="#" class="bg-invert-secondary text-invert w-11 h-11 border-rounded flex items-center justify-center hover:bg-accent transition-all shadow-lg">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="#" class="bg-invert-secondary text-invert w-11 h-11 border-rounded flex items-center justify-center hover:bg-accent transition-all shadow-lg">
                        <i class="fa-brands fa-linkedin"></i>
                    </a>
                    <a href="#" class="bg-invert-secondary text-invert w-11 h-11 border-rounded flex items-center justify-center hover:bg-accent transition-all shadow-lg">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="#" class="bg-invert-secondary text-invert w-11 h-11 border-rounded flex items-center justify-center hover:bg-accent transition-all shadow-lg">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Product -->
            <div>
                <h4 class="text-xl font-bold text-invert mb-6 outfit-font">Product</h4>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-invert-secondary hover:text-accent transition-colors">Features</a></li>
                    <li><a href="#pricing" class="text-invert-secondary hover:text-accent transition-colors">Pricing</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Integrations</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Security</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Changelog</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="text-xl font-bold text-invert mb-6 outfit-font">Resources</h4>
                <ul class="space-y-3">
                    <li><a href="#docs" class="text-invert-secondary hover:text-accent transition-colors">Documentation</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Help Center</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Community</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">System Status</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-xl font-bold text-invert mb-6 outfit-font">Company</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">About</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Blog</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Careers</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors">Partners</a></li>
                </ul>
            </div>

        </div>

        <!-- Newsletter -->
        <div class="bg-invert-secondary border-rounded p-4 sm:p-6 shadow-xl mb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <h4 class="text-2xl font-bold text-invert mb-3 outfit-font">
                        Get Product Updates
                    </h4>
                    <p class="text-invert-secondary">
                        New features, improvements, and platform updates — no spam.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email"
                        placeholder="Your email address"
                        class="flex-1 px-5 py-4 bg-primary text-primary border-primary border-rounded input-focus shadow-lg">
                    <button
                        class="bg-accent text-invert px-6 py-4 border-rounded font-bold hover-invert transition-all shadow-lg">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-top pt-8 flex md:flex-row justify-between items-center gap-6">
            <div class="text-invert-secondary text-sm">
                © {{ date('Y') }} Arzavo. All rights reserved.
            </div>
            <div class="flex flex-wrap justify-center gap-6 text-sm">
                <a href="#" class="text-invert-secondary hover:text-accent transition-colors">Privacy Policy</a>
                <!-- <a href="#" class="text-invert-secondary hover:text-accent transition-colors">Terms</a> -->
            </div>
        </div>

    </div>
</footer>

<!-- Scroll to Top -->
<button id="scrollToTop"
    class="fixed bottom-8 right-8 bg-accent text-invert w-12 h-12 border-rounded shadow-xl hover-invert transition-all opacity-0 pointer-events-none z-50">
    <i class="fa-solid fa-arrow-up"></i>
</button>
