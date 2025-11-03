@extends('layouts.app')
@section('title', 'Create Account - ' . config('app.name'))

@section('content')
<div class="auth-container flex justify-center items-center min-h-dvh">
    <div class="auth-form border-primary border-rounded p-4 bg-primary min-h-dvh md:min-h-auto w-full md:w-auto">
        <img src="{{ asset('images/logo/arzavo-dark.png') }}" alt="Arzavo Logo" class="mb-4 logo">
        <form action="{{ route('register.handle') }}" method="POST">
            @csrf
            <h2 class="text-3xl font-bold mb-4">Create Account!</h2>
            <p class="mb-4 text-tertiary">Create your account by filling the form below.</p>
            <div class="flex mb-2 gap-4">
                <div>
                    <label for="fname" class="block text-sm text-primary">First Name</label>
                    <input type="text" name="fname" required value="{{ old('fname') }}" class="border-primary block w-full border-rounded p-2" placeholder="First Name">
                </div>
                <div>
                    <label for="lname" class="block text-sm text-primary">Last Name</label>
                    <input type="text" name="lname" required value="{{ old('lname') }}" class="border-primary block w-full border-rounded p-2" placeholder="Last Name">
                </div>
            </div>
            <div class="mb-2">
                <label for="email" class="block text-sm text-primary">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="border-primary block w-full border-rounded p-2" placeholder="Email">
            </div>
            <div class="mb-2">
                <label for="number" class="block text-sm text-primary">Number</label>
                <input type="text" name="number" required value="{{ old('number') }}" class="border-primary block w-full border-rounded p-2" placeholder="Phone Number">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm text-primary">Password</label>
                <input type="password" name="password" id="passwordRegisterform" required placeholder="Password" class="border-primary block w-full border-rounded p-2">
            </div>
            <input type="hidden" name="role" value="admin">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="showPasswordRegister" class="cursor-pointer" onclick="document.getElementById('passwordRegisterform').type = this.checked ? 'text' : 'password'">
                    <label for="showPasswordRegister" class="ml-2 text-sm cursor-pointer" style="color:var(--text-color);">Show Password</label>
                </div>
            </div>
            <button type="submit" class="w-full font-bold py-2 bg-invert text-invert border-rounded">Create Now</button>

            <div class="mt-4 text-center">
                <a href="{{ route('login.form') }}" class="text-blue-600 hover:text-blue-500">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>
@endsection