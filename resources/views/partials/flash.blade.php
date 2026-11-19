@if (session('success'))
    <div role="status" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
        {{ session('success') }}
    </div>
@endif
