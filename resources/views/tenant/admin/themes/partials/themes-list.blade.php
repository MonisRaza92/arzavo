<!-- THEMES LIST -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    @foreach($themes as $theme)

        @php
            $tenantTheme = $installedThemes->get($theme->id);
        @endphp

        <div class="border-rounded border-primary bg-primary overflow-hidden">

            {{-- PREVIEW PLACEHOLDER --}}
            <div class="h-40 bg-secondary flex items-center justify-center text-sm text-tertiary border-bottom">
                Theme Preview
            </div>

            <div class="p-4">

                {{-- HEADER --}}
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ $theme->name }}
                        </h3>

                        <p class="mt-1 text-sm text-tertiary">
                            Category: {{ $theme->category ?? 'Education' }}
                        </p>
                    </div>

                    {{-- STATUS BADGE --}}
                    <div class="flex flex-col gap-2">
                        @if($tenantTheme?->status === 'published')
                            <span class="text-xs px-2 py-1 border-rounded border-primary bg-green-100 text-green-700">
                                Active
                            </span>
                        @elseif($tenantTheme?->status === 'draft')
                            <span class="text-xs px-2 py-1 border-rounded border-primary bg-blue-100 text-blue-700">
                                Installed
                            </span>
                        @else
                            <span class="text-xs px-2 py-1 border-rounded border-primary bg-gray-100 text-gray-600">
                                Not Installed
                            </span>
                        @endif
                        <a href="{{ route('website.preview', ['theme' => $theme->slug, 'slug' => 'home'], ) }}"
                            target="_blank" title="See live preview"
                            class="w-full text-center text-xs px-2 py-1 bg-tertiary border-primary border-rounded text-gray-700 hover:text-black transition">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="mt-4 flex flex-col gap-2">

                    {{-- ACTIVE THEME --}}
                    @if($tenantTheme?->status === 'published')
                        <a data-turbo="false" href="{{ route('admin.builder.index', ['theme' => $tenantTheme->theme_slug, 'page' => 'home' ]) }}"
                            class="w-full text-center px-4 py-2 text-sm rounded bg-black text-white hover:bg-gray-800 transition">
                            Customize
                        </a>
                        <button disabled
                            class="w-full px-4 py-2 text-sm rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                            Currently Active
                        </button>

                        {{-- DRAFT THEME --}}
                    @elseif($tenantTheme?->status === 'draft')
                        <a data-turbo="false"
                            href="{{ route('admin.builder.index', ['theme' => $tenantTheme->theme_slug, 'page' => 'home']) }}"
                            class="w-full text-center px-4 py-2 text-sm rounded bg-black text-white hover:bg-gray-800 transition">
                            Customize
                        </a>

                        <form method="POST" action="{{ route('admin.themes.publish', $tenantTheme->id) }}">
                            @csrf
                            <button
                                class="w-full px-4 py-2 text-sm rounded bg-green-600 text-white hover:bg-green-700 transition">
                                Publish
                            </button>
                        </form>

                    @else
                        {{-- NOT INSTALLED --}}
                        <form method="POST" action="{{ route('admin.themes.install', $theme->id) }}">
                            @csrf
                            <button
                                class="w-full px-4 py-2 text-sm rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                                Install Theme
                            </button>
                        </form>
                    @endif

                </div>

            </div>
        </div>

    @endforeach

</div>