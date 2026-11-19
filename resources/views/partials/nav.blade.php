<header class="border-b border-amber-200 bg-white">
    <nav class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight text-orange-800 sm:text-xl">Kaon Ta! Davao – Yuson's Food Guide</a>
        <div class="flex flex-wrap items-center gap-5 text-sm font-medium">
            <a href="{{ route('home') }}" class="hover:text-orange-700">Home</a>
            <a href="{{ route('eateries.index') }}" class="hover:text-orange-700">Eateries</a>
            <a href="{{ route('districts.index') }}" class="hover:text-orange-700">Districts</a>
            <a href="{{ route('eateries.create') }}" class="rounded-full bg-orange-700 px-4 py-2 text-white hover:bg-orange-800">Add Eatery</a>
        </div>
    </nav>
</header>
