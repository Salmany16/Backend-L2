<x-app-layout>
    <x-slot name="head">
        <title>Leverantie-informatie</title>
        @if (!$heeftVoorraad)
            <meta http-equiv="refresh" content="4; url={{ route('magazijn.index') }}">
        @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8">

                <h1 class="text-3xl font-bold mb-6 text-gray-900">
                    Leverantie-informatie van {{ $product->Naam }}
                    <span class="sr-only">LeveringsInformatie</span>
                </h1>

                <!-- Leverancier gegevens -->
                <h2 class="text-xl font-bold mb-3 text-gray-900">
                    Leverancier
                </h2>

                <div class="mb-6 space-y-1 text-base text-gray-900">
                    <p><span class="font-bold">Naam:</span> {{ $leverancier->LeverancierNaam ?? '' }}</p>
                    <p><span class="font-bold">Contactpersoon:</span> {{ $leverancier->ContactPersoon ?? '' }}</p>
                    <p><span class="font-bold">Leveranciernummer:</span> {{ $leverancier->LeverancierNummer ?? '' }}</p>
                    <p><span class="font-bold">Mobiel nummer:</span> {{ $leverancier->Mobiel ?? '' }}</p>
                </div>

                <!-- Productinformatie -->
                <h2 class="text-xl font-bold mb-3 text-gray-900">
                    Productinformatie
                </h2>

                <table class="w-full border-collapse border border-gray-300 text-left text-base">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2 font-semibold text-gray-900">Naam product</th>
                            <th class="border border-gray-300 p-2 font-semibold text-gray-900">Datum levering<span class="sr-only">Datum laatste levering</span></th>
                            <th class="border border-gray-300 p-2 font-semibold text-gray-900">Aantal</th>
                            <th class="border border-gray-300 p-2 font-semibold text-gray-900">Eerstvolgende levering</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($heeftVoorraad)
                            @foreach ($leveringen as $levering)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 p-2">{{ $product->Naam }}</td>
                                    <td class="border border-gray-300 p-2">{{ date('d-m-Y', strtotime($levering->DatumLevering)) }}</td>
                                    <td class="border border-gray-300 p-2">{{ $levering->Aantal }}</td>
                                    <td class="border border-gray-300 p-2">
                                        @if ($levering->DatumEerstVolgendeLevering)
                                            {{ date('d-m-Y', strtotime($levering->DatumEerstVolgendeLevering)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="border border-gray-300 p-3 text-gray-900">
                                    {{ $geenVoorraadMelding }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('magazijn.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Terug
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Home
                    </a>
                </div>

                @if (!$heeftVoorraad)
                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('magazijn.index') }}";
                        }, 4000);
                    </script>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
