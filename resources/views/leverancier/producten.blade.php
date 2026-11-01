<x-app-layout>
    <x-slot name="head">
        @if (!$heeftProducten)
            <meta http-equiv="refresh" content="3; url={{ route('leverancier.index') }}">
        @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Geleverde producten</u>
                </h1>

                <div class="mb-6 space-y-1 text-base text-gray-900">
                    <p><span class="font-semibold">Naam leverancier:</span> {{ $leverancier->Naam }}</p>
                    <p><span class="font-semibold">Contactpersoon:</span> {{ $leverancier->ContactPersoon }}</p>
                    <p><span class="font-semibold">Leveranciersnummer:</span> {{ $leverancier->LeverancierNummer }}</p>
                    <p><span class="font-semibold">Mobiel:</span> {{ $leverancier->Mobiel }}</p>
                </div>

                <table class="w-full border-collapse border border-black text-left text-base">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Naam product</th>
                            <th class="border border-black p-2 font-normal">Aantal in Magazijn</th>
                            <th class="border border-black p-2 font-normal">Verpakkingseenheid</th>
                            <th class="border border-black p-2 font-normal">Laatste levering</th>
                            <th class="border border-black p-2 font-normal text-center">Nieuwe levering</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($heeftProducten)
                            @foreach ($producten as $product)
                                <tr>
                                    <td class="border border-black p-2">
                                        {{ $product->ProductNaam }}
                                    </td>
                                    <td class="border border-black p-2">{{ $product->AantalAanwezig ?? 0 }}</td>
                                    <td class="border border-black p-2">{{ $product->VerpakkingsEenheid !== null ? str_replace('.', ',', (string) $product->VerpakkingsEenheid) : '-' }}</td>
                                    <td class="border border-black p-2">
                                        {{ $product->LaatsteLevering ? date('d-m-Y', strtotime($product->LaatsteLevering)) : '-' }}
                                    </td>
                                    <td class="border border-black p-2 text-center">
                                        <a href="{{ route('leverancier.levering.create', [$leverancier->Id, $product->ProductId]) }}" class="text-green-600 hover:text-green-800 font-bold text-xl" title="Nieuwe levering toevoegen">
                                            +
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-black p-3 text-gray-900">
                                    Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('leverancier.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Terug
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Home
                    </a>
                </div>

                @if (!$heeftProducten)
                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('leverancier.index') }}";
                        }, 3000);
                    </script>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
