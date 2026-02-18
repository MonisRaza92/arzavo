<h2 class="mb-3 font-semibold">Installed Themes</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">

    @foreach($tenantThemes as $tenantT)

        <div class="border-rounded border-primary bg-white overflow-hidden">

            {{-- preview --}}
            <div class="h-44 bg-gray-100 flex items-center justify-center border-bottom relative">
                Theme Preview

                <a href="{{ route('website.preview', ['theme' => $tenantT->theme_slug, 'slug' => 'home']) }}"
                    target="_blank"
                    class="absolute top-2 right-2 bg-white/90 px-2 py-1 border-rounded text-xs shadow hover:bg-white">
                    <i class="fa-solid fa-eye mr-1"></i> Preview
                </a>
            </div>

            <div class="p-4">

                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-lg">
                            {{ ucfirst($tenantT->theme_slug) }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            Version {{ $tenantT->theme_version }}
                        </p>
                    </div>

                    @if($tenantT->status === 'published')
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">
                            Live
                        </span>
                    @else
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                            Draft
                        </span>
                    @endif
                </div>

                {{-- buttons --}}
                <div class="mt-4 flex gap-2">

                    <a data-turbo="false"
                        href="{{ route('admin.builder.index', ['theme' => $tenantT->theme_slug, 'page' => 'home']) }}"
                        class="flex-1 text-center px-4 py-2 text-sm border-rounded bg-black text-white hover:bg-gray-800">
                        Customize
                    </a>

                    <form method="POST" action="{{ route('admin.themes.copy', $tenantT->id) }}">
                        @csrf
                        <button class="px-3 py-2 border-rounded border-primary bg-gray-100 hover:bg-gray-200" title="Duplicate this theme">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </form>

                </div>

                @if($tenantT->status === 'draft')
                    <form method="POST" action="{{ route('admin.themes.publish', $tenantT->id) }}" class="mt-2">
                        @csrf
                        <button class="w-full px-4 py-2 border-rounded bg-green-600 text-white hover:bg-green-700">
                            Publish
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.themes.publish', $tenantT->id) }}" class="mt-2">
                        @csrf
                        <button class="w-full px-4 py-2 border-rounded bg-zinc-400 text-white">
                            Published
                        </button>
                    </form>
                @endif

            </div>

        </div>

    @endforeach
</div>


<h2 class="mb-3 font-semibold">Available Themes</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    @foreach($themes as $theme)

        @php
            $installed = $tenantThemes->firstWhere('theme_id', $theme->id);
        @endphp

        <div class="border-rounded border-primary border-gray-200 bg-white overflow-hidden">

            {{-- preview --}}
            <div class="h-44 bg-gray-100 flex items-center justify-center border-bottom relative">
                Theme Preview

                <a href="{{ route('website.preview', ['theme' => $theme->slug, 'slug' => 'home']) }}" target="_blank"
                    class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded text-xs shadow hover:bg-white">
                    <i class="fa-solid fa-eye mr-1"></i> Preview
                </a>
            </div>

            <div class="p-4">

                <h3 class="font-semibold text-lg">{{ $theme->name }}</h3>

                <p class="text-sm text-gray-500 mb-4">
                    Version {{ $theme->version }}
                </p>

                @if($installed)

                    <a data-turbo="false"
                        href="{{ route('admin.builder.index', ['theme' => $installed->theme_slug, 'page' => 'home']) }}"
                        class="block w-full text-center px-4 py-2 border-rounded bg-black text-white hover:bg-gray-800">
                        Customize
                    </a>

                @else

                    <form method="POST" action="{{ route('admin.themes.install', $theme->id) }}">
                        @csrf
                        <button class="w-full px-4 py-2 border-rounded bg-blue-600 text-white hover:bg-blue-700">
                            Install Theme
                        </button>
                    </form>

                @endif

            </div>

        </div>

    @endforeach
</div>