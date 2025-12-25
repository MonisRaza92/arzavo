<!-- Footer -->
<footer class="bg-invert text-invert-secondary py-20 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-24 h-24 bg-accent border-rounded transform rotate-12"></div>
        <div class="absolute top-32 right-16 w-20 h-20 bg-accent-secondary border-rounded transform -rotate-12"></div>
        <div class="absolute bottom-16 left-1/4 w-16 h-16 bg-accent border-rounded transform rotate-45"></div>
        <div class="absolute bottom-32 right-12 w-28 h-28 bg-accent-secondary border-rounded transform -rotate-30"></div>
    </div>
    
    <div class="container relative z-10">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Company Info -->
            <div class="space-y-8">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo/arzavo-light.png') }}" alt="Arzavo" class="logo">
                    <span class="text-3xl font-bold text-invert outfit-font">Arzavo</span>
                </div>
                <p class="text-invert-secondary leading-relaxed text-lg">
                    Empowering educational institutions with cutting-edge multi-tenant SaaS solutions. Transform your teaching and learning experience with Arzavo.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="bg-invert-secondary text-invert w-12 h-12 border-rounded flex items-center justify-center hover:bg-accent transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="bg-invert-secondary text-invert w-12 h-12 border-rounded flex items-center justify-center hover:bg-accent transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-linkedin text-lg"></i>
                    </a>
                    <a href="#" class="bg-invert-secondary text-invert w-12 h-12 border-rounded flex items-center justify-center hover:bg-accent transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-facebook text-lg"></i>
                    </a>
                    <a href="#" class="bg-invert-secondary text-invert w-12 h-12 border-rounded flex items-center justify-center hover:bg-accent transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Product -->
            <div>
                <h4 class="text-xl font-bold text-invert mb-8 outfit-font">Product</h4>
                <ul class="space-y-4">
                    <li><a href="#features" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Features</a></li>
                    <li><a href="#pricing" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Pricing</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Integrations</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Security</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Updates</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-xl font-bold text-invert mb-8 outfit-font">Support</h4>
                <ul class="space-y-4">
                    <li><a href="#docs" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Documentation</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Help Center</a></li>
                    <li><a href="#contact" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Contact Us</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Community</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Status</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-xl font-bold text-invert mb-8 outfit-font">Company</h4>
                <ul class="space-y-4">
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">About Us</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Blog</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Careers</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Press</a></li>
                    <li><a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 text-lg font-medium">Partners</a></li>
                </ul>
            </div>
        </div>

        <!-- Newsletter Signup -->
        <div class="bg-invert-secondary border-rounded p-12 mb-16 shadow-xl">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h4 class="text-3xl font-bold text-invert mb-4 outfit-font">Stay Updated</h4>
                    <p class="text-invert-secondary text-lg leading-relaxed">Get the latest updates, tips, and educational insights delivered to your inbox.</p>
                </div>
                <div class="flex gap-4">
                    <input type="email" placeholder="Enter your email" 
                           class="flex-1 px-6 py-4 bg-primary text-primary border-primary border-rounded input-focus text-lg shadow-lg">
                    <button class="bg-accent text-invert px-8 py-4 border-rounded font-bold hover-invert transition-all duration-300 shadow-xl transform hover:scale-105">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-invert-secondary pt-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                <div class="text-invert-secondary text-lg">
                    © {{ date('Y') }} Arzavo. All rights reserved.
                </div>
                <div class="flex space-x-8 text-lg">
                    <a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 font-medium">Privacy Policy</a>
                    <a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 font-medium">Terms of Service</a>
                    <a href="#" class="text-invert-secondary hover:text-accent transition-colors duration-300 font-medium">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scrollToTop" class="fixed bottom-10 right-10 bg-accent text-invert w-14 h-14 border-rounded shadow-xl hover-invert transition-all duration-300 opacity-0 pointer-events-none z-50 transform hover:scale-110">
    <i class="fa-solid fa-arrow-up text-xl"></i>
</button>

<script>
// Scroll to Top functionality
window.addEventListener('scroll', function() {
    const scrollToTop = document.getElementById('scrollToTop');
    if (window.pageYOffset > 300) {
        scrollToTop.style.opacity = '1';
        scrollToTop.style.pointerEvents = 'auto';
    } else {
        scrollToTop.style.opacity = '0';
        scrollToTop.style.pointerEvents = 'none';
    }
});

document.getElementById('scrollToTop').addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>