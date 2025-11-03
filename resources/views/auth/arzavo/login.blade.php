@extends('layouts.app')
@section('title', 'Login Account - ' . config('app.name'))

@section('content')
<div class="auth-container container p-4 flex justify-center items-center h-dvh">
    <div class="auth-form w-full md:w-auto h-dvh md:h-auto border-primary bg-primary border-rounded p-4">
        <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo" class="mb-4 logo">
        <form action="{{ route('login.handle') }}" method="POST">
            @csrf
            <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
            <p class="mb-4">Please enter your credentials to access your account.</p>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email or Number</label>
                <input type="email" name="email" required autofocus value="{{ old('email') }}" class="border-primary block w-full border-rounded p-2" placeholder="Enter your email or number">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="passwordLoginformPage" required placeholder="Enter your password" class="border-primary block w-full border-rounded p-2">
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="showPasswordLogin" class="cursor-pointer" onclick="document.getElementById('passwordLoginformPage').type = this.checked ? 'text' : 'password'">
                    <label for="showPasswordLogin" class="ml-2 block text-sm text-gray-900 cursor-pointer">Show Password</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot your password?</a>
                </div>
            </div>
            <button type="submit" class="w-full font-bold py-2 bg-invert text-invert border-rounded">Login</button>
            <div class="mt-4 text-center">
                <a href="{{ route('register.form') }}" class="text-blue-600 hover:text-blue-500">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</div>
@endsection