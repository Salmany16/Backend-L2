<x-app-layout>
    <x-slot name="head">
        @if ($successMelding)
            <meta http-equiv="refresh" content="3; url={{ route('leverancier.details', $leverancier->LeverancierId) }}">
        @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Wijzig Leveranciergegevens</u>
                </h1>

                @if ($successMelding)
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded text-base font-semibold">
                        {{ $successMelding }}
                    </div>

                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('leverancier.details', $leverancier->LeverancierId) }}";
                        }, 3000);
                    </script>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('leverancier.update', $leverancier->LeverancierId) }}" class="max-w-2xl">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="naam" class="block text-base font-normal text-gray-900 mb-1">Naam:</label>
                            <input
                                type="text"
                                id="naam"
                                name="naam"
                                value="{{ old('naam', $leverancier->Naam) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="contactpersoon" class="block text-base font-normal text-gray-900 mb-1">Contactpersoon:</label>
                            <input
                                type="text"
                                id="contactpersoon"
                                name="contactpersoon"
                                value="{{ old('contactpersoon', $leverancier->ContactPersoon) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="leveranciernummer" class="block text-base font-normal text-gray-900 mb-1">Leveranciernummer:</label>
                            <input
                                type="text"
                                id="leveranciernummer"
                                name="leveranciernummer"
                                value="{{ old('leveranciernummer', $leverancier->LeverancierNummer) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="mobiel" class="block text-base font-normal text-gray-900 mb-1">Mobiel:</label>
                            <input
                                type="text"
                                id="mobiel"
                                name="mobiel"
                                value="{{ old('mobiel', $leverancier->Mobiel) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="straatnaam" class="block text-base font-normal text-gray-900 mb-1">Straatnaam:</label>
                            <input
                                type="text"
                                id="straatnaam"
                                name="straatnaam"
                                value="{{ old('straatnaam', $leverancier->Straatnaam) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="huisnummer" class="block text-base font-normal text-gray-900 mb-1">Huisnummer:</label>
                            <input
                                type="number"
                                id="huisnummer"
                                name="huisnummer"
                                value="{{ old('huisnummer', $leverancier->Huisnummer) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="postcode" class="block text-base font-normal text-gray-900 mb-1">Postcode:</label>
                            <input
                                type="text"
                                id="postcode"
                                name="postcode"
                                value="{{ old('postcode', $leverancier->Postcode) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>

                        <div>
                            <label for="stad" class="block text-base font-normal text-gray-900 mb-1">Stad:</label>
                            <input
                                type="text"
                                id="stad"
                                name="stad"
                                value="{{ old('stad', $leverancier->Stad) }}"
                                class="w-full border border-black p-2"
                                required
                            >
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <button type="submit" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded">
                            Sla Op
                        </button>
                        <div class="flex gap-3">
                            <a href="{{ route('leverancier.details', $leverancier->LeverancierId) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                Terug
                            </a>
                            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                                Home
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
