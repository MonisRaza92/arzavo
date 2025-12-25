@extends('layouts.app')
@section('title', 'About Us - Arzavo Educational Platform')
@section('content')
@include('arzavo.partials.navbar')

<!-- About Hero -->
<section class="bg-secondary py-20">
    <div class="container">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-4xl lg:text-5xl font-bold text-primary mb-6">
                Transforming Education Through Technology
            </h1>
            <p class="text-xl text-secondary leading-relaxed mb-8">
                At Arzavo, we believe every educational institution deserves a powerful, customizable platform to deliver exceptional learning experiences. Our mission is to democratize access to advanced educational technology.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register.form') }}" class="bg-accent text-invert px-8 py-4 border-rounded font-semibold hover-invert transition-all duration-300 text-lg">
                    <i class="fa-solid fa-rocket mr-2"></i>
                    Start Your Journey
                </a>
                <a href="#team" class="bg-primary text-primary border-primary px-8 py-4 border-rounded font-semibold hover-primary transition-all duration-300 text-lg">
                    <i class="fa-solid fa-users mr-2"></i>
                    Meet Our Team
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="bg-primary py-20">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-primary mb-6">Our Story</h2>
                <div class="space-y-6 text-secondary leading-relaxed">
                    <p>
                        Founded in 2024, Arzavo emerged from a simple observation: educational institutions were struggling with outdated, inflexible platforms that couldn't adapt to their unique needs. We saw schools, colleges, and coaching centers forced to compromise their vision due to technological limitations.
                    </p>
                    <p>
                        Our founders, with decades of combined experience in education technology and software development, set out to create something different. We envisioned a platform that would be powerful enough for large institutions yet simple enough for small coaching centers to use effectively.
                    </p>
                    <p>
                        Today, Arzavo serves over 500 educational institutions across India, empowering them to create branded, feature-rich platforms that truly reflect their identity and serve their students better.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="bg-accent text-invert p-8 border-rounded">
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div>
                            <div class="text-4xl font-bold mb-2">500+</div>
                            <div class="text-sm opacity-90">Institutions Served</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">50K+</div>
                            <div class="text-sm opacity-90">Students Empowered</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">99.9%</div>
                            <div class="text-sm opacity-90">Platform Uptime</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">24/7</div>
                            <div class="text-sm opacity-90">Support Available</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="bg-secondary py-20">
    <div class="container">
        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-primary border-primary border-rounded p-8">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mb-6">
                    <i class="fa-solid fa-bullseye text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary mb-4">Our Mission</h3>
                <p class="text-secondary leading-relaxed">
                    To democratize access to advanced educational technology by providing institutions of all sizes with powerful, customizable platforms that enhance teaching and learning experiences while maintaining complete data ownership and brand identity.
                </p>
            </div>
            
            <div class="bg-primary border-primary border-rounded p-8">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mb-6">
                    <i class="fa-solid fa-eye text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary mb-4">Our Vision</h3>
                <p class="text-secondary leading-relaxed">
                    To become the leading multi-tenant educational platform globally, enabling every educational institution to deliver world-class digital learning experiences while fostering innovation in education technology and pedagogical approaches.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="bg-primary py-20">
    <div class="container">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-primary mb-6">Our Core Values</h2>
            <p class="text-xl text-secondary max-w-3xl mx-auto">
                These principles guide everything we do at Arzavo, from product development to customer support.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-accent text-invert w-20 h-20 border-rounded flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-heart text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-4">Student-Centric</h3>
                <p class="text-secondary">
                    Every feature we build is designed with the end goal of improving student learning outcomes and experiences.
                </p>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-20 h-20 border-rounded flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-shield-halved text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-4">Security First</h3>
                <p class="text-secondary">
                    We prioritize data security and privacy, ensuring complete isolation and protection for every institution.
                </p>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-20 h-20 border-rounded flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-lightbulb text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-4">Innovation</h3>
                <p class="text-secondary">
                    We continuously innovate to stay ahead of educational technology trends and user needs.
                </p>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-20 h-20 border-rounded flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-handshake text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-4">Partnership</h3>
                <p class="text-secondary">
                    We view our clients as partners, working together to achieve their educational goals and vision.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section id="team" class="bg-secondary py-20">
    <div class="container">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-primary mb-6">Meet Our Team</h2>
            <p class="text-xl text-secondary max-w-3xl mx-auto">
                Our diverse team of educators, technologists, and innovators is passionate about transforming education through technology.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Team Member 1 -->
            <div class="bg-primary border-primary border-rounded p-8 text-center hover-primary transition-all duration-300">
                <div class="w-24 h-24 bg-accent border-rounded mx-auto mb-6 flex items-center justify-center">
                    <i class="fa-solid fa-user text-invert text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Rajesh Kumar</h3>
                <p class="text-accent font-semibold mb-3">Founder & CEO</p>
                <p class="text-secondary text-sm mb-4">
                    15+ years in EdTech. Former engineering leader at top tech companies. Passionate about democratizing education.
                </p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-tertiary hover:text-accent transition-colors">
                        <i class="fa-brands fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-tertiary hover:text-accent transition-colors">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="bg-primary border-primary border-rounded p-8 text-center hover-primary transition-all duration-300">
                <div class="w-24 h-24 bg-accent border-rounded mx-auto mb-6 flex items-center justify-center">
                    <i class="fa-solid fa-user text-invert text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Priya Sharma</h3>
                <p class="text-accent font-semibold mb-3">Co-Founder & CTO</p>
                <p class="text-secondary text-sm mb-4">
                    Full-stack architect with expertise in scalable SaaS platforms. Former principal engineer at unicorn startups.
                </p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-tertiary hover:text-accent transition-colors">
                        <i class="fa-brands fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-tertiary hover:text-accent transition-colors">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="bg-primary border-primary border-rounded p-8 text-center hover-primary transition-all duration-300">
                <div class="w-24 h-24 bg-accent border-rounded mx-auto mb-6 flex items-center justify-center">
                    <i class="fa-solid fa-user text-invert text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Dr. Amit Patel</h3>
                <p class="text-accent font-semibold mb-3">Head of Education</p>
                <p class="text-secondary text-sm mb-4">
                    PhD in Educational Technology. 20+ years in curriculum design and educational research. Former university professor.
                </p>
                <div class="flex justify-center space-x-3">
                    <a href="#" class="text-tertiary hover:text-accent transition-colors">
                        <i class="fa-brands fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-tertiary hover:text-accent transition-colors">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join Us -->
<section class="bg-primary py-20">
    <div class="container">
        <div class="text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-primary mb-6">Join Our Mission</h2>
            <p class="text-xl text-secondary max-w-3xl mx-auto mb-8">
                We're always looking for passionate individuals who want to make a difference in education. Join our team and help shape the future of learning.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="bg-accent text-invert px-8 py-4 border-rounded font-semibold hover-invert transition-all duration-300 text-lg">
                    <i class="fa-solid fa-briefcase mr-2"></i>
                    View Open Positions
                </a>
                <a href="#contact" class="bg-secondary text-primary border-primary px-8 py-4 border-rounded font-semibold hover-primary transition-all duration-300 text-lg">
                    <i class="fa-solid fa-envelope mr-2"></i>
                    Get In Touch
                </a>
            </div>
        </div>
    </div>
</section>

@include('arzavo.partials.footer')
@endsection