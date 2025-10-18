<header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/90 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-app-logo-icon class="size-7 text-zinc-900 dark:text-white" />
                <span class="font-semibold">Pediatric Clinic</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
                <a href="{{ route('about') }}" class="hover:text-blue-600">About</a>
                <a href="{{ route('features') }}" class="hover:text-blue-600">Features</a>
                <a href="{{ route('sections') }}" class="hover:text-blue-600">Sections</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex rounded-lg bg-blue-600 px-4 py-2 text-white shadow-sm hover:bg-blue-700">Book Appointment</a>
              
                <!-- Mobile Page Dropdown -->
                <div class="md:hidden">
                    <label class="sr-only" for="mobile-nav-select">Navigate</label>
                    <select id="mobile-nav-select" class="rounded-md border px-2.5 py-2 text-sm dark:border-zinc-700"
                        onchange="if (this.value) window.location.href=this.value">
                        <option value="{{ route('home') }}" {{ request()->routeIs('home') ? 'selected' : '' }}>Home</option>
                        <option value="{{ route('about') }}" {{ request()->routeIs('about') ? 'selected' : '' }}>About</option>
                        <option value="{{ route('features') }}" {{ request()->routeIs('features') ? 'selected' : '' }}>Features</option>
                        <option value="{{ route('sections') }}" {{ request()->routeIs('sections') ? 'selected' : '' }}>Sections</option>
                        <option value="{{ route('register.show') }}" {{ request()->routeIs('register.*') ? 'selected' : '' }}>Register</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</header>