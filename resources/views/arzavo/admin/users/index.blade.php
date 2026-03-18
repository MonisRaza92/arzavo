@extends('layouts.arzavo')
@section('title', 'Users Managment')

@section('content')

    @include('arzavo.admin.users.header')
    {{-- LIST --}}
    <div class="flex flex-col gap-3">

        @forelse($users as $user)

            <div class="bg-primary border-primary border-rounded py-2 px-4 hover:bg-hover-secondary transition">

                <div class="flex justify-between items-center gap-4">

                    {{-- LEFT: USER INFO --}}
                    <div class="flex items-center gap-4 flex-1">

                        <x-profile-image :user="$user" />

                        {{-- DETAILS --}}
                        <div class="flex flex-col">

                            <div class="flex items-center gap-2">

                                <h3 class="font-medium text-sm text-primary">
                                    {{ $user->full_name }}
                                </h3>

                                {{-- STATUS --}}
                                @if($user->status === 'active')
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-500/10 text-green-600">
                                        Active
                                    </span>
                                @else
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500/10 text-red-600">
                                        Suspended
                                    </span>
                                @endif

                            </div>

                            <div class="text-xs text-tertiary">
                                {{ '@' . $user->username ?? null }} • {{ $user->email }}
                            </div>

                            {{-- META --}}
                            <div class="text-[11px] text-tertiary mt-1 flex gap-4 flex-wrap">

                                <span>
                                    <i class="fa-solid fa-user mr-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>

                                <span>
                                    <i class="fa-solid fa-building mr-1"></i>
                                    {{ $user->tenants->count() }} Tenants
                                </span>

                                <span>
                                    <i class="fa-solid fa-clock mr-1"></i>
                                    {{ $user->last_login ? $user->last_login->diffForHumans() : 'No login' }}
                                </span>

                                <span>
                                    <i class="fa-solid fa-calendar mr-1"></i>
                                    Joined {{ $user->created_at->format('d M Y') }}
                                </span>

                            </div>

                        </div>

                    </div>

                    {{-- RIGHT: ACTIONS --}}
                    <div class="flex flex-col items-center gap-1">

                        {{-- TOGGLE --}}
                        <form action="{{ route('arzavo.admin.users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="px-3 py-1 w-20 text-xs border-primary bg-tertiary border-rounded hover-primary">

                                {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>

                        {{-- DELETE --}}
                        <form action="{{ route('arzavo.admin.users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-1 w-20 text-xs bg-accent text-invert border-rounded hover:opacity-90">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="bg-primary border-primary border-rounded p-6 text-center text-tertiary">
                No users found
            </div>

        @endforelse

    </div>


@endsection