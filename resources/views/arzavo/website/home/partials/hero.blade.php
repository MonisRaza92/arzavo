{{-- Hero Section --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16"
    style="background: linear-gradient(135deg, #fff 0%, #fff8f8 40%, #fffdf5 70%, #fff 100%);">

    {{-- Background gradient blobs --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 -right-32 w-[600px] h-[600px] rounded-full opacity-30"
            style="background: radial-gradient(circle, rgba(146,0,0,0.12) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-20 -left-20 w-[500px] h-[500px] rounded-full opacity-25"
            style="background: radial-gradient(circle, rgba(197,132,0,0.12) 0%, transparent 70%);"></div>

        {{-- Floating elements --}}
        <div class="absolute top-28 right-[18%] w-5 h-5 rounded-full bg-accent opacity-15 animate-float"></div>
        <div class="absolute top-52 left-[12%] w-3 h-3 bg-amber-400 opacity-20 rotate-45 animate-float"
            style="animation-delay:0.8s;"></div>
        <div class="absolute bottom-40 right-[22%] w-4 h-4 rounded-full bg-accent opacity-10 animate-float"
            style="animation-delay:1.3s;"></div>
        <div class="absolute bottom-28 left-[28%] w-2.5 h-2.5 bg-amber-500 opacity-15 rounded animate-float"
            style="animation-delay:0.4s;"></div>
    </div>

    <div class="container grid grid-cols-1 lg:grid-cols-2 gap-16 relative z-10 py-20">
        <div class="left-content flex flex-col gap-6">
            <h1 class="text-[48px] md:text-[64px] lg:text-[78px] leading-none text-dark font-semibold">Run Your Entire
                <span class="relative inline-flex h-12 lg:h-16 overflow-hidden bg-accent/10">
                    <span id="heading-titles" class="flex flex-col gap-2 font-mono lg:gap-4 pl-1 pr-3 text-accent italic animate-slide-up">
                        <span class="h-12 lg:h-16 text-[44px] md:text-[60px] lg:text-[70px] flex items-center">INSTITUTE</span>
                        <span class="h-12 lg:h-16 text-[44px] md:text-[60px] lg:text-[70px] flex items-center">ACADEMY</span>
                        <span class="h-12 lg:h-16 text-[44px] md:text-[60px] lg:text-[70px] flex items-center">SCHOOL</span>
                        <span class="h-12 lg:h-16 text-[44px] md:text-[60px] lg:text-[70px] flex items-center">COLLEGE</span>
                        <span class="h-12 lg:h-16 text-[44px] md:text-[60px] lg:text-[70px] flex items-center">COACHING</span>

                        <!-- Duplicate first item for seamless loop -->
                        <span class="h-12 lg:h-16 text-[44px] md:text-[60px] lg:text-[70px] flex items-center">INSTITUTE</span>
                    </span>
                </span>
                From<br> One Platform
            </h1>
            <p class="text-dark/90 font-light text-lg">Manage admissions, students, teachers, attendance, fees, exams,
                communication, and daily operations from a single, easy-to-use platform. Arzavo helps educational
                institutions save time, reduce manual work, and stay organized as they grow.</p>
            <div class="flex flex-col md:flex-row items-center gap-4">
                <x-button url="{{ route('register.form') }}" class="w-full! md:w-auto!" padding="px-6 py-3">Get Started
                    <i class="fa-solid fa-arrow-right -rotate-45"></i></x-button>
                <x-button url="{{ route('login.form') }}" class="w-full! md:w-auto!" padding="px-6 py-3"
                    variant="accent">Request For Demo <i class="fa-solid fa-right-to-bracket"></i></x-button>
            </div>

            <div class="stats grid grid-cols-2 md:grid-cols-4 items-center gap-6 mt-5">
                <div class="stat flex items-center justify-center md:justify-start">
                    <div>
                        <div class="text-5xl font-bold text-black">100+</div>
                        <div class="text-dark/80 text-sm">INSTITUTES</div>
                    </div>
                </div>
                <div class="stat flex items-center justify-center md:justify-start">
                    <div>
                        <div class="text-5xl font-bold text-black">10K+</div>
                        <div class="text-dark/80 text-sm">ACTIVE USERS</div>
                    </div>
                </div>
                <div class="stat flex items-center justify-center md:justify-start">
                    <div>
                        <div class="text-5xl font-bold text-black">99.8%</div>
                        <div class="text-dark/80 text-sm">SERVER UPTIME</div>
                    </div>
                </div>
                <div class="stat flex items-center justify-center md:justify-start">
                    <div>
                        <div class="text-5xl font-bold text-black">24/7</div>
                        <div class="text-dark/80 text-sm">LIVE SUPPORT</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-content">
            <div class="grid grid-cols-2 gap-4 h-full">
                <div class="flex flex-col gap-4">
                    <img src="{{ asset('images/website/2151696436.jpg') }}" alt="" class="w-full object-cover rounded-lg">
                    <img src="{{ asset('images/website/20006.jpg') }}" alt="" class="w-full h-30 object-cover rounded-lg">
                </div>
                <div class="flex flex-col gap-4">
                    <img src="{{ asset('images/website/20006.jpg') }}" alt="" class="w-full h-30 object-cover rounded-lg">
                    <img src="{{ asset('images/website/2151737340.jpg') }}" alt="" class="w-full object-cover rounded-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Global accent vars */
    .bg-accent {
        background-color: #920000 !important;
    }

    .text-accent {
        color: #920000 !important;
    }

    .hover\:text-accent:hover {
        color: #920000 !important;
    }

    .bg-amber-400 {
        background-color: #FBBF24;
    }

    /* Animations */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(18px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-float {
        animation: float 5s ease-in-out infinite;
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out both;
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out both;
    }

    /* Mobile / Tablet slide-up animation (Height: 48px + Gap: 8px = 56px steps) */
    @keyframes slide-up {
        0%, 15% {
            transform: translateY(0);
        }
        20%, 35% {
            transform: translateY(-52px);
        }
        40%, 55% {
            transform: translateY(-104px);
        }
        60%, 75% {
            transform: translateY(-156px);
        }
        80%, 95% {
            transform: translateY(-208px);
        }
        98%, 100% {
            transform: translateY(-260px);
        }
    }

    /* Desktop slide-up animation (Height: 64px + Gap: 16px = 80px steps) */
    @media (min-width: 1024px) {
        @keyframes slide-up {
            0%, 15% {
                transform: translateY(0);
            }
            20%, 35% {
                transform: translateY(-80px);
            }
            40%, 55% {
                transform: translateY(-160px);
            }
            60%, 75% {
                transform: translateY(-240px);
            }
            80%, 95% {
                transform: translateY(-320px);
            }
            98%, 100% {
                transform: translateY(-400px);
            }
        }
    }

    .animate-slide-up {
        animation: slide-up 10s infinite;
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('typewriter', () => ({
            text: '',
            words: ['Run Everything.', 'Scale Faster.', 'Automate Daily.', 'Grow Enrollments.'],
            wordIndex: 0, isDeleting: false,
            init() { this.type(); },
            type() {
                const w = this.words[this.wordIndex];
                this.text = this.isDeleting ? w.substring(0, this.text.length - 1) : w.substring(0, this.text.length + 1);
                let speed = this.isDeleting ? 50 : 100;
                if (!this.isDeleting && this.text === w) { speed = 2000; this.isDeleting = true; }
                else if (this.isDeleting && this.text === '') { this.isDeleting = false; this.wordIndex = (this.wordIndex + 1) % this.words.length; speed = 500; }
                setTimeout(() => this.type(), speed);
            }
        }))
    });
</script>