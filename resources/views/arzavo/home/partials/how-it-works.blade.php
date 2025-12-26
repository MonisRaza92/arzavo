<!-- How Arzavo Works -->
<section class="bg-primary py-20 relative overflow-hidden">

    <!-- Soft Background -->
    <div class="absolute inset-0 hidden sm:block opacity-5">
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-accent border-rounded rotate-12"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-accent-secondary border-rounded -rotate-12"></div>
    </div>

    <div class="container relative z-10">

        <!-- Header -->
        <div class="text-center mx-auto mb-16 lg:mb-24 px-2">
            <div class="inline-flex items-center bg-accent-subtle text-accent px-5 py-2 border-rounded text-sm font-semibold mb-6">
                <i class="fa-solid fa-lightbulb mr-2"></i>
                How It Works
            </div>

            <h2 class="text-4xl sm:text-4xl lg:text-6xl font-bold text-primary mb-6 leading-tight">
                From Signup to
                <span class="text-accent relative">
                    Your Own Website
                </span>
            </h2>

            <p class="text-base sm:text-lg lg:text-xl text-secondary leading-relaxed">
                Arzavo automatically creates a <strong>separate website, admin panel, and database</strong>
                for every institute — no technical setup required.
            </p>
        </div>

        <!-- Steps -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-10 mb-20">

            <!-- Step 1 -->
            <div class="bg-secondary border-primary border-rounded p-6 lg:p-8 text-center shadow-xl hover-primary transition-all transform lg:hover:-translate-y-2">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
                <span class="text-accent text-sm font-bold">STEP 1</span>
                <h3 class="text-xl font-bold text-primary mt-2 mb-3">
                    Sign Up & Choose Domain
                </h3>
                <p class="text-secondary text-sm leading-relaxed">
                    Create your account and choose a subdomain or connect your own custom domain.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-secondary border-primary border-rounded p-6 lg:p-8 text-center shadow-xl hover-primary transition-all transform lg:hover:-translate-y-2">
                <div class="bg-accent-secondary text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fa-solid fa-building text-xl"></i>
                </div>
                <span class="text-accent-secondary text-sm font-bold">STEP 2</span>
                <h3 class="text-xl font-bold text-primary mt-2 mb-3">
                    Institute Setup
                </h3>
                <p class="text-secondary text-sm leading-relaxed">
                    Add institute details, logo, roles, and settings. Arzavo creates an isolated system instantly.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-secondary border-primary border-rounded p-6 lg:p-8 text-center shadow-xl hover-primary transition-all transform lg:hover:-translate-y-2">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fa-solid fa-palette text-xl"></i>
                </div>
                <span class="text-accent text-sm font-bold">STEP 3</span>
                <h3 class="text-xl font-bold text-primary mt-2 mb-3">
                    Build Your Website
                </h3>
                <p class="text-secondary text-sm leading-relaxed">
                    Design pages using the drag-and-drop builder. Customize colors, fonts, and sections.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-secondary border-primary border-rounded p-6 lg:p-8 text-center shadow-xl hover-primary transition-all transform lg:hover:-translate-y-2">
                <div class="bg-accent-secondary text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fa-solid fa-rocket text-xl"></i>
                </div>
                <span class="text-accent-secondary text-sm font-bold">STEP 4</span>
                <h3 class="text-xl font-bold text-primary mt-2 mb-3">
                    Launch & Scale
                </h3>
                <p class="text-secondary text-sm leading-relaxed">
                    Start enrolling students, managing courses, fees, staff, and grow without limits.
                </p>
            </div>

        </div>

        <!-- IMAGE SLOT -->
        <!-- IMAGE SLOT: How Arzavo Creates Separate Websites (AI Illustration / Dashboard Flow) -->

        <!-- Key Guarantees -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-10 mb-20">
            <div class="bg-secondary border-primary border-rounded p-6 text-center shadow-lg">
                <i class="fa-solid fa-database text-accent text-3xl mb-4"></i>
                <h4 class="text-lg font-bold text-primary mb-2">Fully Isolated Data</h4>
                <p class="text-tertiary text-sm">
                    Every institute runs on its own database with complete data privacy.
                </p>
            </div>
            <div class="bg-secondary border-primary border-rounded p-6 text-center shadow-lg">
                <i class="fa-solid fa-globe text-accent text-3xl mb-4"></i>
                <h4 class="text-lg font-bold text-primary mb-2">Separate Public Website</h4>
                <p class="text-tertiary text-sm">
                    Each user gets a standalone website — not a shared page or sub-panel.
                </p>
            </div>
            <div class="bg-secondary border-primary border-rounded p-6 text-center shadow-lg">
                <i class="fa-solid fa-shield-halved text-accent text-3xl mb-4"></i>
                <h4 class="text-lg font-bold text-primary mb-2">Secure by Design</h4>
                <p class="text-tertiary text-sm">
                    Role-based access, session isolation, and secure authentication.
                </p>
            </div>
        </div>

        <!-- Demo / CTA -->
        <div class="bg-secondary border-primary border-rounded p-8 sm:p-12 text-center shadow-xl">
            <h3 class="text-2xl sm:text-3xl font-bold text-primary mb-4">
                See How Arzavo Builds a Website for You
            </h3>
            <p class="text-secondary max-w-2xl mx-auto mb-8">
                Watch how a new institute gets its own website and system in minutes.
            </p>

            <!-- VIDEO / IMAGE SLOT -->
            <!-- IMAGE / VIDEO SLOT: Arzavo Demo -->

            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
                <a href="{{ route('register.form') }}"
                   class="bg-accent text-invert px-8 py-4 border-rounded font-bold hover-invert transition-all shadow-lg">
                    <i class="fa-solid fa-rocket mr-2"></i>
                    Start Free Trial
                </a>
                <a href="{{ route('documentation') }}"
                   class="bg-primary text-primary border-primary px-8 py-4 border-rounded font-bold hover-primary transition-all shadow-lg">
                    <i class="fa-solid fa-book mr-2"></i>
                    Read Docs
                </a>
            </div>
        </div>

    </div>
</section>
