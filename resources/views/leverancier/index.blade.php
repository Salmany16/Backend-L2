<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Overzicht leveranciers</u>
                </h1>

                <table class="w-full border-collapse border border-black text-left text-base">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Naam</th>
                            <th class="border border-black p-2 font-normal">Contactpersoon</th>
                            <th class="border border-black p-2 font-normal">Leveranciersnummer</th>
                            <th class="border border-black p-2 font-normal">Mobiel</th>
                            <th class="border border-black p-2 font-normal text-center">Aantal verschillende producten</th>
                            <th class="border border-black p-2 font-normal text-center">Toon producten</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leveranciers as $leverancier)
                            <tr>
                                <td class="border border-black p-2">{{ $leverancier->Naam }}</td>
                                <td class="border border-black p-2">{{ $leverancier->ContactPersoon }}</td>
                                <td class="border border-black p-2">{{ $leverancier->LeverancierNummer }}</td>
                                <td class="border border-black p-2">{{ $leverancier->Mobiel }}</td>
                                <td class="border border-black p-2 text-center">{{ $leverancier->AantalVerschillendeProducten }}</td>
                                <td class="border border-black p-2 text-center">
                                    <a href="{{ route('leverancier.producten', $leverancier->Id) }}" class="text-blue-600 hover:text-blue-800 text-xl" title="Toon producten van {{ $leverancier->Naam }}">
                                        📦
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
