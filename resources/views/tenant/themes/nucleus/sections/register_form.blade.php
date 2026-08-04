<div {!! $section->attributes() !!} class="arz-section relative overflow-hidden {{ $section->visibility }}" style="{{ $section->margin . $section->padding }}">
    {!! $section->backgrounds() !!}
    <div class="mx-auto {{ $section->form_width ?? 'max-w-md' }} relative z-10">
        <div class="p-8 border-rounded bg-primary border-primary space-y-6">
            <div class="text-center space-y-2">
                <h2 class="text-2xl font-extrabold text-primary tracking-tight">
                    {{ $section->title ?? 'Create Your Account' }}
                </h2>
                @if(!empty($section->subtitle))
                    <p class="text-xs text-secondary">
                        {{ $section->subtitle }}
                    </p>
                @endif
            </div>

            <form action="{{ route_to('register.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-primary block mb-1">First Name</label>
                        <input type="text" name="fname" required value="{{ old('fname') }}" placeholder="First Name"
                               class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-primary block mb-1">Last Name</label>
                        <input type="text" name="lname" required value="{{ old('lname') }}" placeholder="Last Name"
                               class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-primary block mb-1">Email Address</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@domain.com"
                           class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                </div>

                <div>
                    <label class="text-xs font-bold text-primary block mb-1">Phone Number</label>
                    <input type="text" name="number" required value="{{ old('number') }}" placeholder="Phone Number"
                           class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                </div>

                <div>
                    <label class="text-xs font-bold text-primary block mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Min. 8 characters"
                           class="w-full p-3 bg-primary border-primary border-rounded text-xs text-primary input-focus">
                </div>

                <button type="submit" 
                        class="w-full py-3 border-rounded font-bold text-xs transition shadow-xs flex items-center justify-center gap-2 {{ $section->button_type ?? 'arz-btn-primary' }}">
                    {{ $section->button_text ?? 'Create Account' }} <i class="fa-solid fa-user-check text-[11px]"></i>
                </button>
            </form>

            @if($section->show_login_link ?? true)
                <div class="text-center pt-2 border-top text-xs text-secondary">
                    <a href="{{ route_to('login') }}" class="font-bold text-primary hover:underline">
                        {!! html_entity_decode($section->login_link_text ?? 'Already have an account? Sign In') !!}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
