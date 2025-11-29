<div class="my-4 flex flex-col lg:flex-row gap-4">
    <div class="teachers lg:w-1/3 border-rounded p-4 pb-0 bg-primary border-primary">
        <h2 class="text-2xl font-bold pb-3 relative text-primary">Teachers <a href="" class="absolute right-0 top-1.5 text-sm" style="color: var(--text-color);">View all <i class="fas fa-arrow-right"></i></a></h2>
        <ul>
            @foreach($teachers->sortByDesc('id')->take(6) as $teacher)
            <li class="flex items-center gap-4 py-3 border-top">
                <!-- Profile Picture -->
                @if ($teacher->profile_picture)
                <img src="{{ $teacher->profile_picture ?? 'https://via.placeholder.com/50' }}"
                    alt="{{ $teacher->fname }} {{ $teacher->lname }}"
                    class="w-11 h-10.5 border-rounded object-cover border">
                @else
                <h2 class="font-bold border-rounded text-3xl flex justify-center items-center w-11 h-10.5 bg-invert text-invert">{{ strtoupper(substr($teacher->fname, 0, 1)) }}</h2>
                @endif

                <!-- User Info -->
                <div class="flex-1">
                    <p class="font-medium text-primary">
                        {{ $teacher->fname }} {{ $teacher->lname }}
                    </p>
                    <p class="text-sm text-tertiary">
                        {{ Str::limit($teacher->email, 18) }}
                    </p>
                </div>

                <!-- Role & Status -->
                <div class="text-right">
                    <span class="ml-2 text-sm px-2 py-1 rounded 
                        {{ $teacher->status === 'active' ? 'text-primary bg-secondary' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($teacher->status) }}
                    </span>
                    <p class="text-xs text-tertiary mt-1">
                        {{ $teacher->created_at->format('d M Y') }}
                    </p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="users lg:w-2/3 border-rounded p-4 pb-0 bg-primary border-primary">
        <h2 class="text-2xl font-bold pb-3 relative text-primary">Recent Users <a href="" class="absolute right-0 top-1.5 text-sm" style="color: var(--text-color);">View all <i class="fas fa-arrow-right"></i></a></h2>
        <ul>
            @foreach($students->sortByDesc('id')->take(6) as $user)
            <li class="flex items-center gap-4 py-3 border-top">
                <!-- Profile Picture -->
                @if ($user->profile_picture)
                <img src="{{ $user->profile_picture ?? 'https://via.placeholder.com/50' }}"
                    alt="{{ $user->fname }} {{ $user->lname }}"
                    class="w-11 h-10.5 border-rounded object-cover border">
                @else
                <h2 class="font-bold border-rounded text-3xl flex justify-center items-center w-11 h-10.5 bg-invert text-invert">{{ strtoupper(substr($user->fname, 0, 1)) }}</h2>
                @endif

                <!-- User Info -->
                <div class="flex-1">
                    <p class="font-medium text-primary">
                        {{ $user->fname }} {{ $user->lname }}
                    </p>
                    <p class="text-sm text-tertiary">
                        {{ $user->email }}
                    </p>
                </div>

                <!-- Role & Status -->
                <div class="text-right">
                    <span class="text-sm px-2 py-1 rounded 
                        {{ $user->role === 'user' ? 'bg-secondary text-primary' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <p class="text-xs text-tertiary mt-1">
                        Joined {{ $user->created_at->format('d M Y') }}
                    </p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>