<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? config('app.name')])
        <!-- Modern font: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            :root { --font-sans: 'Inter', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, 'Apple Color Emoji', 'Segoe UI Emoji'; }
            html, body { font-family: var(--font-sans); text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased; }
        </style>
    </head>
    <body class="min-h-screen bg-white bg-doodles text-zinc-800 dark:bg-zinc-900 dark:text-zinc-100">
        @include('partials.header')

        <main class="min-h-[60vh]">
            @yield('content')
        </main>

        <!-- Mobile Bottom Nav -->
        <nav class="fixed bottom-0 inset-x-0 z-50 border-t border-zinc-200 bg-white/90 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80 md:hidden">
            <div class="mx-auto max-w-7xl px-4">
                <div class="grid grid-cols-4 text-center">
                    <a href="{{ route('home') }}" class="py-2 text-xs {{ request()->routeIs('home') ? 'text-blue-600 font-medium' : 'text-zinc-700 dark:text-zinc-300' }}">Home</a>
                    <a href="{{ route('features') }}" class="py-2 text-xs {{ request()->routeIs('features') ? 'text-blue-600 font-medium' : 'text-zinc-700 dark:text-zinc-300' }}">Features</a>
                    <a href="{{ route('about') }}" class="py-2 text-xs {{ request()->routeIs('about') ? 'text-blue-600 font-medium' : 'text-zinc-700 dark:text-zinc-300' }}">About</a>
                    <a href="{{ route('register.show') }}" class="py-2 text-xs {{ request()->routeIs('register.*') ? 'text-blue-600 font-medium' : 'text-zinc-700 dark:text-zinc-300' }}">Register</a>
                </div>
            </div>
        </nav>

        @include('partials.footer')

        {{-- Flash status hook for SweetAlert2 (JS separated) --}}
        @if (session('status'))
            <div id="flash-status" data-message="{{ e(session('status')) }}" hidden></div>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endif
    </body>
</html>