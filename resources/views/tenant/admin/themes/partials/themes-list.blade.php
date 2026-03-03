<h2 class="mb-3 mt-6 font-semibold"><i class="fa-regular fa-cards-blank mr-1"></i> Installed Themes</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">

    @foreach($tenantThemes as $tenantT)

        <div class="p-2 border-rounded border-primary bg-primary relative">

            {{-- Preview --}}
            <a href="{{ route('website.preview', ['theme' => $tenantT->theme_slug, 'slug' => 'home', 'theme_id' => $tenantT->id]) }}"
                target="_blank">
                <div
                    class="bg-gray-100 aspect-video flex items-center justify-center border-primary border-rounded relative">
                    Preview
                    @if($tenantT->status === 'published')
                        <span class="absolute top-2 right-2 text-xs text-green-600 rounded">
                            <i class="fa-regular fa-circle-dot mr-1 text-[10px]"></i> Published
                        </span>
                    @else
                        <span class="absolute top-2 right-2 text-xs text-blue-700 rounded">
                            <i class="fa-regular fa-cloud-check mr-1 text-[10px]"></i> Installed
                        </span>
                    @endif

                </div>
            </a>

            <div class="py-2 pl-1">

                {{-- Title + Menu --}}
                <div class="flex justify-between items-start relative">

                    <div>
                        <h3 class="font-semibold">
                            {{ ucfirst($tenantT->theme_slug) }}
                        </h3>

                        <p class="text-xs text-gray-500">
                            Version {{ $tenantT->theme_version }}
                        </p>
                    </div>

                    {{-- Ellipsis --}}
                    <button onclick="toggleModel('theme-actions-{{ $tenantT->id }}')"
                        class="border-rounded hover:bg-gray-100">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="theme-actions-{{ $tenantT->id }}"
                        class="hidden absolute right-0 p-1 top-10 w-48 bg-white border border-gray-200 rounded shadow-lg z-20">

                        @if($tenantT->status === 'draft')
                            <form method="POST" action="{{ route('admin.themes.publish', $tenantT->id) }}">
                                @csrf
                                <button class="text-sm px-3 py-2 hover:bg-gray-100 w-full text-left">
                                    <i class="fa-regular fa-circle-dot mr-1"></i> Publish Theme
                                </button>
                            </form>
                        @endif
                        <a data-turbo="false"
                            href="{{ route('admin.builder.index', ['theme' => $tenantT->theme_slug, 'status' => $tenantT->status, 'theme_id' => $tenantT->id, 'is_active' => $tenantT->is_active, 'page' => 'home']) }}"
                            class="block px-3 py-2 text-sm hover:bg-gray-100">
                            <i class="fa-regular fa-sliders mr-1"></i> Customize
                        </a>

                        <form method="POST" action="{{ route('admin.themes.copy', $tenantT->id) }}">
                            @csrf
                            <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100">
                                <i class="fa-jelly fa-regular fa-clone mr-1"></i> Duplicate
                            </button>
                        </form>

                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100">
                            <i class="fa-solid fa-code mr-1"></i> Edit Code
                        </button>

                        <!-- <button class="w-full text-left px-3 mb-1 py-2 text-sm hover:bg-gray-100">
                            <i class="fa-regular fa-gear mr-1"></i> Default Content
                        </button> -->

                        <form action="{{ route('admin.themes.destroy', $tenantT->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="w-full text-left px-3 py-2 border-top text-sm text-red-600 hover:bg-red-50">
                                <i class="fa-regular fa-trash mr-1"></i> Delete Theme
                            </button>
                        </form>

                    </div>

                </div>
            </div>

        </div>

    @endforeach
</div>

<h2 class="mb-3 mt-6 font-semibold"><i class="fa-solid fa-rectangle-history-circle-plus mr-1"></i> Available Themes</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    @foreach($themes as $theme)

        @php
            $installed = $tenantThemes->firstWhere('theme_id', $theme->id);
        @endphp

        <div class="overflow-hidden p-2 border-rounded border-primary bg-primary">

            {{-- preview --}}
            <a href="{{ route('website.preview', ['theme' => $theme->slug, 'slug' => 'home', 'theme_id' => $theme->id]) }}"
                target="_blank">
                <div
                    class="bg-gray-100 aspect-video flex items-center justify-center border-primary border-rounded relative">
                    Preview
                </div>
            </a>

            <div class="p-2 pb-1 flex justify-between items-center">

                <div>
                    <h3 class="font-semibold">{{ $theme->name }}</h3>
                    <h3 class="text-xs text-tertiary">Version {{ $theme->version }}</h3>
                </div>
                <div class="flex flex-col items-end">
                    @if ($theme->is_paid)
                        <span class="text-sm text-tertiary font-semibold">₹ {{ $theme->price }}</span>
                    @endif
                    @if($installed)
                        <span class="text-sm text-tertiary italic">Installed</span>
                    @else
                        <form method="POST" action="{{ route('admin.themes.install', $theme->id) }}">
                            @csrf
                            <button class="w-full text-sm text-blue-600 hover:text-blue-800">
                                Install <i class="fa-regular fa-rectangle-history-circle-plus text-xs ml-1"></i>
                            </button>
                        </form>
                    @endif

                </div>

            </div>

        </div>

    @endforeach
</div>