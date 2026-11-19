@php
    $editing = $eatery !== null;
    $selectedFeatures = old('features', $eatery?->features?->modelKeys() ?? []);
@endphp

<form method="POST" action="{{ $editing ? route('eateries.update', $eatery) : route('eateries.store') }}" class="space-y-5">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            <p class="font-semibold">Please fix the following errors:</p>
            <ul class="mt-2 list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="name" class="block text-sm font-semibold">Eatery name</label>
        <input id="name" name="name" value="{{ old('name', $eatery?->name) }}" required maxlength="100" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
        @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="district_id" class="block text-sm font-semibold">District</label>
        <select id="district_id" name="district_id" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
            <option value="">Choose a district</option>
            @foreach ($districts as $district)
                <option value="{{ $district->id }}" @selected(old('district_id', $eatery?->district_id) == $district->id)>{{ $district->name }}</option>
            @endforeach
        </select>
        @error('district_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="address" class="block text-sm font-semibold">Address</label>
        <input id="address" name="address" value="{{ old('address', $eatery?->address) }}" required maxlength="255" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
        @error('address') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="price_range" class="block text-sm font-semibold">Price range</label>
            <select id="price_range" name="price_range" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                <option value="">Choose a range</option>
                @foreach (['budget' => '₱ Budget', 'mid' => '₱₱ Mid-range', 'splurge' => '₱₱₱ Splurge'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('price_range', $eatery?->price_range) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('price_range') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="specialty" class="block text-sm font-semibold">Specialty <span class="font-normal text-stone-500">(optional)</span></label>
            <input id="specialty" name="specialty" value="{{ old('specialty', $eatery?->specialty) }}" maxlength="100" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
            @error('specialty') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>
    <div>
        <span class="block text-sm font-semibold">Features</span>
        <div class="mt-2 grid gap-2 sm:grid-cols-2">
            @foreach ($features as $feature)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="features[]" value="{{ $feature->id }}" @checked(in_array($feature->id, $selectedFeatures)) class="rounded border-stone-300 text-orange-700 focus:ring-orange-600">
                    {{ $feature->name }}
                </label>
            @endforeach
        </div>
        @error('features') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        @foreach ($features as $feature)
            @if ($errors->has('features.'.$feature->id))
                <p class="mt-1 text-sm text-red-700">{{ $errors->first('features.'.$feature->id) }}</p>
            @endif
        @endforeach
    </div>
    <div class="rounded-xl bg-amber-50 p-4">
        <h2 class="font-semibold">Business permit</h2>
        <div class="mt-3 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="permit_no" class="block text-sm font-semibold">Permit number</label>
                <input id="permit_no" name="permit_no" value="{{ old('permit_no', $eatery?->permit?->permit_no) }}" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                @error('permit_no') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="expires_on" class="block text-sm font-semibold">Expiry date</label>
                <input id="expires_on" type="date" name="expires_on" value="{{ old('expires_on', $eatery?->permit?->expires_on?->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                @error('expires_on') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
    <div>
        <label class="flex items-center gap-2 text-sm font-semibold">
            <input type="checkbox" name="is_open" value="1" @checked(old('is_open', $eatery?->is_open ?? true)) class="rounded border-stone-300 text-orange-700 focus:ring-orange-600">
            Currently open
        </label>
        @error('is_open') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-full bg-orange-700 px-6 py-3 font-semibold text-white hover:bg-orange-800">{{ $editing ? 'Save changes' : 'Add eatery' }}</button>
        <a href="{{ $editing ? route('eateries.show', $eatery) : route('eateries.index') }}" class="rounded-full border border-stone-300 px-6 py-3 font-semibold text-stone-700 hover:bg-stone-50">Cancel</a>
    </div>
</form>
