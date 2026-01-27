<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <x-header />
    <style>
        [x-cloak] {
            display: none !important;
        }

        .builder-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .builder-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .builder-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .builder-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.8);
        }

        /* Dragging styles */
        .sortable-ghost {
            opacity: 0.4;
            background-color: var(--color-accent-soft, #f0f9ff);
        }

        .sortable-drag {
            cursor: grabbing;
        }
    </style>
</head>

<body class="h-full bg-primary text-secondary overflow-hidden font-sans antialiased">

    {{-- Main Container --}}
    <div id="app" class="h-full flex flex-col">
        @yield('content')
    </div>

    {{-- Global Toast/Alerts --}}
    <x-alert />

</body>

</html>