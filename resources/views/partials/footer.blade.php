<footer class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">About Us</h3>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400">Compassionate pediatric care focused on your child’s wellness.</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Contact</h3>
                <ul class="mt-3 space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>123 Care Street, Health City</li>
                    <li>(555) 123-4567</li>
                    <li>hello@pediatricclinic.com</li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Hours</h3>
                <ul class="mt-3 space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>Mon–Fri: 8am–6pm</li>
                    <li>Sat: 9am–2pm</li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Follow</h3>
                <div class="mt-3 flex gap-3 text-zinc-600 dark:text-zinc-400">
                    <a href="#" class="hover:text-blue-600">Twitter</a>
                    <a href="#" class="hover:text-blue-600">Facebook</a>
                    <a href="#" class="hover:text-blue-600">Instagram</a>
                </div>
            </div>
        </div>
        <div class="mt-8 flex items-center justify-between border-t border-zinc-200 pt-6 dark:border-zinc-800">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">© {{ date('Y') }} Pediatric Clinic. All rights reserved.</p>
            <a href="{{ route('home') }}" class="text-sm hover:text-blue-600">Back to Home</a>
        </div>
    </div>
</footer>