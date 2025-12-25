{{-- THEMES GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    @foreach($themes as $theme)

    @php
    $isActive = app('currentTheme') === $theme->slug;
    @endphp

    <div class="border-rounded border-primary bg-primary overflow-hidden">

        {{-- PREVIEW PLACEHOLDER --}}
        <div class="h-40 bg-gray-100 flex items-center justify-center text-sm text-gray-400">
            Theme Preview
        </div>

        <div class="p-4">

            <div class="flex justify-between items-start">
                <div>
                    {{-- THEME NAME --}}
                    <h3 class="text-lg font-semibold text-primary">
                        {{ $theme->name }}
                    </h3>

                    {{-- META --}}
                    <div class="mt-1 text-sm text-secondary">
                        Category: {{ $theme->category ?? 'Education' }}
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mt-3">
                    @if($isActive)
                    <span class="inline-block text-xs px-2 py-1 rounded bg-green-100 text-green-700">
                        Active
                    </span>
                    @else
                    <span class="inline-block text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">
                        Available
                    </span>
                    @endif
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-4 gap-2 flex flex-col gap-2">

                {{-- Customize or Apply Button --}}

                @if($isActive)
                <a href="{{ route('admin.builder.index') }}"
                    class="flex-1 text-center px-4 py-2 text-sm border-rounded
                                      bg-black text-white hover:bg-gray-800 transition">
                    Customize
                </a>
                @else
                <form method="POST"
                    action="{{ route('admin.themes.apply', ['id' => $theme->id]) }}"
                    class="flex-1">
                    @csrf
                    <button
                        class="w-full px-4 py-2 text-sm border-rounded
                                           bg-blue-600 text-white hover:bg-blue-700 transition">
                        Apply Theme
                    </button>
                </form>
                @endif
                <!-- Preview Button -->
                <div class="flex-1">
                    <a href=""
                        class="w-full inline-block text-center px-4 py-2 text-sm border-rounded
                                           bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                        Preview
                    </a>
                </div>
            </div>

        </div>
    </div>

    @endforeach

</div>