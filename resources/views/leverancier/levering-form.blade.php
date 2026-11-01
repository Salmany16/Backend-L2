<x-app-layout>
    <x-slot name="head">
        @if ($errorMelding)
            <meta http-equiv="refresh" content="4; url={{ route('leverancier.producten', $leverancier->Id) }}">
        @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Levering Product</u>
                </h1>

                <div class="mb-6 space-y-1 text-base text-gray-900">
                    <p><span class="font-semibold">Product:</span> {{ $product->Naam }}</p>
                    <p><span class="font-semibold">Leverancier:</span> {{ $leverancier->Naam }}</p>
                    <p><span class="font-semibold">Contactpersoon:</span> {{ $leverancier->ContactPersoon }}</p>
                    <p><span class="font-semibold">Mobiel:</span> {{ $leverancier->Mobiel }}</p>
                </div>

                @if ($errorMelding)
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $errorMelding }}
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('leverancier.producten', $leverancier->Id) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            Terug
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            Home
                        </a>
                    </div>

                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('leverancier.producten', $leverancier->Id) }}";
                        }, 4000);
                    </script>
                @else
                    <form method="POST" action="{{ route('leverancier.levering.store', [$leverancier->Id, $product->Id]) }}">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="aantal" class="block text-base font-semibold text-gray-900 mb-1">
                                Aantal producteenheden
                            </label>
                            <input
                                type="number"
                                id="aantal"
                                name="aantal"
                                min="1"
                                value="{{ old('aantal') }}"
                                class="border border-black p-2 w-40"
                                required
                            >
                        </div>

                        <div class="mb-6">
                            <label for="datum_eerstvolgende" class="block text-base font-semibold text-gray-900 mb-1">
                                Datum eerstvolgende levering
                            </label>
                            <input
                                type="date"
                                id="datum_eerstvolgende"
                                name="datum_eerstvolgende"
                                value="{{ old('datum_eerstvolgende') }}"
                                class="border border-black p-2"
                                required
                            >
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <button type="submit" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                                Sla op
                            </button>
                            <div class="flex gap-3">
                                <a href="{{ route('leverancier.producten', $leverancier->Id) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                    Terug
                                </a>
                                <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                    Home
                                </a>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
