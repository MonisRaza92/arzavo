@extends('layouts.app')
@section('title', 'Create Account - ' . config('app.name'))

@section('content')
    <div class="auth-container flex justify-center items-center h-dvh">
        <div class="hidden lg:block w-3/4 h-full">
            <img src="{{ asset('images/auth/register.jpg') }}" alt="" class="inset-0 object-cover w-full h-full">
        </div>
        <div
            class="auth-form w-full flex flex-col items-center gap-4 lg:gap-0 lg:w-1/4 h-full relative border-primary bg-primary">
            <div class="bg-primary max-w-lg p-4 lg:px-6 border-bottom w-full">
                <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo" class="logo">
            </div>
            <div class="max-w-lg p-4 h-full overflow-y-auto lg:p-6">
                <form action="{{ route('register.handle') }}" method="POST">
                    @csrf
                    <h2 class="text-3xl font-bold mb-4">Create Account!</h2>
                    <p class="mb-4 text-tertiary">Create your account by filling the form below.</p>
                    <div class="flex mb-2 gap-4">
                        <div>
                            <label for="fname" class="block text-sm text-primary">First Name</label>
                            <input type="text" name="fname" required value="{{ old('fname') }}"
                                class="border-primary block w-full border-rounded p-2" placeholder="First Name">
                        </div>
                        <div>
                            <label for="lname" class="block text-sm text-primary">Last Name</label>
                            <input type="text" name="lname" required value="{{ old('lname') }}"
                                class="border-primary block w-full border-rounded p-2" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="block text-sm text-primary">Email</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            class="border-primary block w-full border-rounded p-2" placeholder="Email">
                    </div>
                    <div class="mb-2">
                        <label for="number" class="block text-sm text-primary">Number</label>
                        <input type="text" name="number" required value="{{ old('number') }}"
                            class="border-primary block w-full border-rounded p-2" placeholder="Phone Number">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm text-primary">Password</label>
                        <input type="password" name="password" id="passwordRegisterform" required placeholder="Password"
                            class="border-primary block w-full border-rounded p-2">
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="showPasswordRegister" class="cursor-pointer"
                                onclick="document.getElementById('passwordRegisterform').type = this.checked ? 'text' : 'password'">
                            <label for="showPasswordRegister" class="ml-2 text-sm cursor-pointer"
                                style="color:var(--text-color);">Show Password</label>
                        </div>
                    </div>
                    <button type="submit" class="w-full font-bold py-2 bg-invert text-invert border-rounded">Create
                        Now</button>

                    <div class="mt-4 text-center">
                        <a href="{{ route('login.form') }}" class="text-blue-600 hover:text-blue-500">Already have an
                            account?
                            Login</a>
                    </div>
                </form>
                <div class="mt-6 text-center">
                    <div class="h-0.5 w-full bg-tertiary border-rounded my-8 flex items-center justify-center"><span
                            class="text-primary bg-primary text-xs py-1 px-2">OR CONTINUE WITH</span></div>
                    <!-- <a href="{{ route('social.redirect', 'google') }}"
                        class="p-2 flex items-center justify-center border-primary w-full border-rounded relative hover:bg-gray-100">
                        <img src="{{ asset('images/auth/google.svg') }}" alt=""
                            class="h-5 absolute left-2 top-1/2 transform -translate-y-1/2"> Continue with Google
                    </a> -->
                    <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline"
                        data-text="continue_with" data-shape="rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="g_id_onload" data-client_id="{{ config('services.google.client_id') }}" data-context="signin"
        data-ux_mode="popup" data-auto_prompt="true" data-callback="handleCredentialResponse" data-auto_select="true"
        data-itp_support="true">
    </div>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        function handleCredentialResponse(response) {
            fetch('/auth/google/onetap', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    credential: response.credential
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    }
                });
        }
    </script>
@endsection