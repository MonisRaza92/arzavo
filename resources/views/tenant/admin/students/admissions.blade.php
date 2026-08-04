@extends('layouts.admin')
@section('title', 'Admin - Student Admissions')
@section('content')
<div class="rounded-md p-6 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
    <h2 class="text-2xl font-bold mb-2" style="color: var(--primary-color);">Student Admissions Form</h2>
    <p class="text-sm text-gray-500 mb-6">Register a new academic student in the coaching/school database.</p>

    <form action="{{ route('tenant.register.handle') }}" method="POST" class="space-y-6 max-w-3xl">
        @csrf
        <input type="hidden" name="role" value="student">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-color);">First Name *</label>
                <input type="text" name="fname" required placeholder="First Name" class="w-full rounded-md p-3" style="background-color: var(--background-color); border: 1px solid var(--border-color); color: var(--text-color);">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-color);">Last Name *</label>
                <input type="text" name="lname" required placeholder="Last Name" class="w-full rounded-md p-3" style="background-color: var(--background-color); border: 1px solid var(--border-color); color: var(--text-color);">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-color);">Email Address *</label>
                <input type="email" name="email" required placeholder="student@example.com" class="w-full rounded-md p-3" style="background-color: var(--background-color); border: 1px solid var(--border-color); color: var(--text-color);">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-color);">Phone Number *</label>
                <input type="text" name="number" required placeholder="Phone Number" class="w-full rounded-md p-3" style="background-color: var(--background-color); border: 1px solid var(--border-color); color: var(--text-color);">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-color);">Password *</label>
                <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full rounded-md p-3" style="background-color: var(--background-color); border: 1px solid var(--border-color); color: var(--text-color);">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-color);">Class *</label>
                <select name="class_id" required class="w-full rounded-md p-3" style="background-color: var(--background-color); border: 1px solid var(--border-color); color: var(--text-color);">
                    <option value="">Select Class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="default-button uppercase px-6 py-3 font-bold">
                Register Student
            </button>
        </div>
    </form>
</div>
@endsection
