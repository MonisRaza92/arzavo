@extends('layouts.admin')
@section('title', 'Admin Users')
@section('content')
@include('admin.partials.user_stats')

<div class="users">
    <div class="rounded-md p-4 pb-0 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <h2 class="text-2xl font-bold mb-4" style="color: var(--primary-color);">All Users</h2>

        <div class="">
            @foreach($users->sortByDesc('id') as $user)
            <div class="grid grid-cols-3 items-center justify-between p-2 rounded-sm" style="border-top: 1px solid var(--border-color);">

                <!-- Profile -->
                <div class="flex items-center gap-4">
                    @if ($user->profile_picture)
                    <img src="{{ asset($user->profile_picture) }}"
                        alt="{{ $user->fname }} {{ $user->lname }}"
                        class="w-14 h-14 rounded-md object-cover border">
                    @else
                    <h2 class="font-bold text-2xl flex justify-center items-center w-14 h-14 rounded-md"
                        style="background-color: var(--primary-color); color: var(--text-light);">
                        {{ strtoupper(substr($user->fname, 0, 1)) }}
                    </h2>
                    @endif

                    <!-- Basic Info -->
                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $user->fname }} {{ $user->lname }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $user->email }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $user->number }}
                        </p>
                    </div>
                </div>

                <!-- Extra Info -->
                <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-sm px-2">
                    <p><span class="font-medium">City:</span> {{ $user->address ?? 'Not Updated' }}</p>
                    <p><span class="font-medium">Course:</span> {{ $user->course ?? 'Not Updated' }}</p>
                    <p><span class="font-medium">Class:</span> {{ $user->class->name ?? 'Not Updated' }}</p>
                    <p><span class="font-medium">Subject:</span> {{ $user->subject->name ?? 'Not Updated' }}</p>
                </div>

                <!-- Role / Status / Joined -->
                <div class="text-right">
                    <span onclick="if(confirm('Are you sure you want to change the role of this user?')) { event.preventDefault(); document.getElementById('userRoleForm{{ $user->id }}').submit(); }" class="px-2 cursor-pointer! py-1 text-xs rounded 
                            {{ $user->role === 'student' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span onclick="if(confirm('Are you sure you want to change the status of this user?')) { event.preventDefault(); document.getElementById('userStatusForm{{ $user->id }}').submit(); }" class="ml-2 cursor-pointer! px-2 py-1 text-xs rounded 
                    {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                    <p class="text-xs text-gray-400 mt-1">
                        Joined {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>
                <form id="userRoleForm{{ $user->id }}" action="{{ route('update-user-role') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                </form>
                <form id="userStatusForm{{ $user->id }}" action="{{ route('update-user-status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection