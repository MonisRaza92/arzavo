<!-- Contact Section -->
<section id="contact" class="bg-primary py-24 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-3">
        <div class="absolute top-20 left-20 w-32 h-32 bg-accent border-rounded transform rotate-12"></div>
        <div class="absolute top-48 right-24 w-24 h-24 bg-accent-secondary border-rounded transform -rotate-12"></div>
        <div class="absolute bottom-32 left-1/4 w-20 h-20 bg-accent border-rounded transform rotate-45"></div>
        <div class="absolute bottom-16 right-20 w-28 h-28 bg-accent-secondary border-rounded transform -rotate-30"></div>
    </div>
    
    <div class="container relative z-10">
        <div class="grid lg:grid-cols-2 gap-20 items-center">
            <!-- Left Content -->
            <div class="space-y-10">
                <div>
                    <h2 class="text-5xl lg:text-6xl font-bold text-primary mb-8 outfit-font">
                        Ready to Transform Your 
                        <span class="text-accent relative">
                            Educational Institution?
                            <div class="absolute -bottom-2 left-0 w-full h-1 bg-accent opacity-30 border-rounded"></div>
                        </span>
                    </h2>
                    <p class="text-xl lg:text-2xl text-secondary leading-relaxed">
                        Get in touch with our team to discuss your requirements and see how Arzavo can help you create the perfect educational platform for your institution.
                    </p>
                </div>

                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-6 bg-secondary p-6 border-rounded shadow-lg hover-primary transition-all duration-300">
                        <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-envelope text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary text-xl outfit-font">Email Us</h4>
                            <p class="text-secondary text-lg">support@arzavo.in</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6 bg-secondary p-6 border-rounded shadow-lg hover-primary transition-all duration-300">
                        <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-phone text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary text-xl outfit-font">Call Us</h4>
                            <p class="text-secondary text-lg">+91 98765 43210</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6 bg-secondary p-6 border-rounded shadow-lg hover-primary transition-all duration-300">
                        <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-map-marker-alt text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary text-xl outfit-font">Visit Us</h4>
                            <p class="text-secondary text-lg">Mumbai, Maharashtra, India</p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="flex space-x-6 pt-6">
                    <a href="#" class="bg-secondary text-primary w-16 h-16 border-rounded flex items-center justify-center hover-primary transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-twitter text-2xl"></i>
                    </a>
                    <a href="#" class="bg-secondary text-primary w-16 h-16 border-rounded flex items-center justify-center hover-primary transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-linkedin text-2xl"></i>
                    </a>
                    <a href="#" class="bg-secondary text-primary w-16 h-16 border-rounded flex items-center justify-center hover-primary transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-facebook text-2xl"></i>
                    </a>
                    <a href="#" class="bg-secondary text-primary w-16 h-16 border-rounded flex items-center justify-center hover-primary transition-all duration-300 shadow-lg transform hover:scale-110">
                        <i class="fa-brands fa-instagram text-2xl"></i>
                    </a>
                </div>
            </div>

            <!-- Right Content - Contact Form -->
            <div class="bg-secondary border-primary border-rounded p-10 shadow-xl transform hover:scale-105 transition-all duration-500">
                <h3 class="text-3xl font-bold text-primary mb-8 outfit-font">Send us a Message</h3>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-secondary font-semibold mb-3 text-lg">First Name</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary text-lg shadow-lg">
                        </div>
                        <div>
                            <label for="last_name" class="block text-secondary font-semibold mb-3 text-lg">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required 
                                   class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary text-lg shadow-lg">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-secondary font-semibold mb-3 text-lg">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary text-lg shadow-lg">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-secondary font-semibold mb-3 text-lg">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary text-lg shadow-lg">
                    </div>
                    
                    <div>
                        <label for="institution" class="block text-secondary font-semibold mb-3 text-lg">Institution Name</label>
                        <input type="text" id="institution" name="institution" 
                               class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary text-lg shadow-lg">
                    </div>
                    
                    <div>
                        <label for="students_count" class="block text-secondary font-semibold mb-3 text-lg">Number of Students</label>
                        <select id="students_count" name="students_count" 
                                class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary text-lg shadow-lg">
                            <option value="">Select range</option>
                            <option value="1-50">1-50 students</option>
                            <option value="51-100">51-100 students</option>
                            <option value="101-500">101-500 students</option>
                            <option value="501-1000">501-1000 students</option>
                            <option value="1000+">1000+ students</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-secondary font-semibold mb-3 text-lg">Message</label>
                        <textarea id="message" name="message" rows="5" required 
                                  class="w-full px-6 py-4 bg-primary border-primary border-rounded input-focus text-primary resize-none text-lg shadow-lg"
                                  placeholder="Tell us about your requirements..."></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-accent text-invert px-8 py-5 border-rounded font-bold hover-invert transition-all duration-300 text-xl shadow-xl transform hover:scale-105">
                        <i class="fa-solid fa-paper-plane mr-3"></i>
                        Send Message
                    </button>
                </form>
                
                <div class="mt-8 p-6 bg-accent-subtle border-rounded shadow-lg">
                    <p class="text-accent text-lg font-medium">
                        <i class="fa-solid fa-info-circle mr-3"></i>
                        We typically respond within 24 hours. For urgent inquiries, please call us directly.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom CTA -->
        <div class="text-center mt-24 bg-secondary border-primary border-rounded p-16 shadow-xl">
            <h3 class="text-4xl font-bold text-primary mb-6 outfit-font">
                Start Your Educational Revolution Today
            </h3>
            <p class="text-xl lg:text-2xl text-secondary mb-10 max-w-3xl mx-auto leading-relaxed">
                Join hundreds of educational institutions already using Arzavo to deliver exceptional learning experiences.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('register.form') }}" class="bg-accent text-invert px-10 py-5 border-rounded font-bold hover-invert transition-all duration-300 text-xl shadow-xl transform hover:scale-105">
                    <i class="fa-solid fa-rocket mr-3"></i>
                    Start Free Trial
                </a>
                <a href="#docs" class="bg-primary text-primary border-primary px-10 py-5 border-rounded font-bold hover-primary transition-all duration-300 text-xl shadow-lg">
                    <i class="fa-solid fa-book mr-3"></i>
                    View Documentation
                </a>
            </div>
        </div>
    </div>
</section>