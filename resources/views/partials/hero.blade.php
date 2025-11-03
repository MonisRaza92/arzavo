<section class="hero-section w-full pb-10 pt-2 md:pt-10">
  <div class="container mx-auto px-6 flex flex-col gap-6 md:flex-row items-center justify-between">

    <!-- Left: Text Content -->
    <div class="md:w-1/2 mt-10 md:mt-0">
      <h6 class="text-lg font-semibold italic mb-3 text-secondary">{{ $settings['site_name'] ?? 'Arzaq Insights' }}</h6>
      <h1 class="text-3xl lg:text-5xl font-extrabold mb-5 text-primary">{{ $customizes['hero_heading'] ?? 'Digitize Your School or Coaching — Everything in One Smart Platform!' }}</h1>
      <p class="mb-8 text-md text-tertiary">{{ $customizes['hero_paragraph'] ?? 'Manage courses, notes, tests, and students with ease. One powerful platform to simplify teaching, boost productivity, and grow your institute smarter and faster.' }}</p>
      <div class="flex gap-4 flex-wrap">
        <a href="{{ $customizes['hero_button1_link'] ?? '#' }}" class="uppercase hover-primary border-invert lg:w-60 w-full md:w-40 text-center bg-invert text-invert border-rounded font-bold py-3">{{ $customizes['hero_button1_text'] ?? 'Get Started' }}</a>
        <a href="{{ $customizes['hero_button2_link'] ?? '#' }}" class="uppercase hover-primary border-invert lg:w-60 w-full md:w-40 text-center bg-invert text-invert border-rounded font-bold py-3">{{ $customizes['hero_button2_text'] ?? 'Explore' }}</a>
      </div>
      <div class="flex justify-between lg:justify-start lg:gap-7  mt-7 w-full"><span class="font-semibold text-sm lg:text-lg"><i class="fa-solid fa-video"></i> ONLINE CLASSES</span><span class="font-semibold text-sm lg:text-lg"><i class="fa-solid fa-chalkboard-teacher"></i> OFFLINE CLASSES</span><span class="font-semibold text-sm lg:text-lg"><i class="fa-solid fa-trophy"></i> RESULTS</span></div>
    </div>

    <!-- Right: Image -->
    <div class="md:w-1/2 flex justify-center hero-img relative mt-4 md:mt-0">
      @if ($customizes['hero_image'])
      <img src="{{ asset($customizes['hero_image']) }}" alt="NEET IIT Coaching"
        class="w-full object-cover object-top hover:object-bottom transition-all duration-900 delay-700 border-rounded animate-fade-in-up">
      @else  
      <img src="{{ asset('images/dashboard-demo.png') }}" alt="NEET IIT Coaching"
      class="w-full lg:max-h-[370px] object-cover object-top hover:object-bottom transition-all duration-900 delay-700 border-rounded border-invert animate-fade-in-up">
      @endif
    </div>

  </div>
</section>