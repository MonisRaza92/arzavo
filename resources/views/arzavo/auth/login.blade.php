@extends('layouts.app')
@section('title', 'Login Account - ' . config('app.name'))

@section('content')
    <div class="auth-container flex justify-center items-center h-dvh">
        <div class="hidden lg:block w-3/4 h-full">
            <img src="{{ asset('images/auth/login.jpg') }}" alt="" class="inset-0 object-cover overflow-hidden w-full h-full">
        </div>
        <div class="auth-form w-full flex flex-col items-center gap-4 lg:w-1/4 h-full relative border-primary bg-primary">
            <div class="bg-primary max-w-sm p-4 lg:px-6 border-bottom w-full">
                <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo" class="logo">
            </div>
            <div class="max-w-lg h-full flex flex-col justify-center items-center p-4 lg:p-6">
                <form action="{{ route('login.handle') }}" method="POST">
                    @csrf
                    <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
                    <p class="mb-4">Please enter your credentials to access your account.</p>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email or Number</label>
                        <input type="email" name="email" required autofocus value="{{ old('email') }}"
                            class="border-primary block w-full border-rounded p-2" placeholder="Enter your email or number">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="passwordLoginformPage" required
                            placeholder="Enter your password" class="border-primary block w-full border-rounded p-2">
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="showPasswordLogin" class="cursor-pointer"
                                onclick="document.getElementById('passwordLoginformPage').type = this.checked ? 'text' : 'password'">
                            <label for="showPasswordLogin" class="ml-2 block text-sm text-gray-900 cursor-pointer">Show
                                Password</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot your password?</a>
                        </div>
                    </div>
                    <button type="submit" class="w-full font-bold py-2 bg-invert text-invert border-rounded">Login</button>
                    <div class="mt-4 text-center">
                        <a href="{{ route('register.form') }}" class="text-blue-600 hover:text-blue-500">Don't have an
                            account?
                            Register</a>
                    </div>
                </form>
                {{-- Google Login (Commented out for now)
                <div class="mt-6 text-center w-full">
                    <div class="h-0.5 w-full bg-tertiary border-rounded my-8 flex items-center justify-center"><span
                            class="text-primary bg-primary text-xs py-1 px-2">OR CONTINUE WITH</span></div>
                    <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline"
                        data-text="continue_with" data-shape="rounded">
                    </div>
                </div>
                --}}
            </div>
        </div>
    </div>
    {{-- Google One-Tap Login (Commented out for now)
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
    --}}
@endsection