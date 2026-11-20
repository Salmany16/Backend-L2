<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Overzicht Leveranciers</u>
                </h1>

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <table class="w-full border-collapse border border-black text-left text-base">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Naam</th>
                            <th class="border border-black p-2 font-normal">Contactpersoon</th>
                            <th class="border border-black p-2 font-normal">Leveranciernummer</th>
                            <th class="border border-black p-2 font-normal">Mobiel</th>
                            <th class="border border-black p-2 font-normal text-center">Leverancier Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leveranciers as $leverancier)
                            <tr>
                                <td class="border border-black p-2">{{ $leverancier->Naam }}</td>
                                <td class="border border-black p-2">{{ $leverancier->ContactPersoon }}</td>
                                <td class="border border-black p-2">{{ $leverancier->LeverancierNummer }}</td>
                                <td class="border border-black p-2">{{ $leverancier->Mobiel }}</td>
                                <td class="border border-black p-2 text-center text-xl">
                                    <a href="{{ route('leverancier.details', $leverancier->Id) }}" title="Leverancier Details bekijken" class="hover:opacity-75">
                                        ✏️
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-black p-3 text-center text-gray-500">
                                    Geen leveranciers gevonden
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Server-side pagination (max 4 records per page) --}}
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Pagina {{ $leveranciers->currentPage() }} van {{ $leveranciers->lastPage() }} (Totaal {{ $leveranciers->total() }} leveranciers)
                    </div>

                    <div class="flex gap-2">
                        @if ($leveranciers->onFirstPage())
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 border border-gray-300 rounded cursor-not-allowed">Vorige</span>
                        @else
                            <a href="{{ $leveranciers->previousPageUrl() }}" class="px-3 py-1 bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 rounded">Vorige</a>
                        @endif

                        @for ($i = 1; $i <= $leveranciers->lastPage(); $i++)
                            @if ($i == $leveranciers->currentPage())
                                <span class="px-3 py-1 bg-blue-600 text-white font-bold border border-blue-600 rounded">{{ $i }}</span>
                            @else
                                <a href="{{ $leveranciers->url($i) }}" class="px-3 py-1 bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 rounded">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($leveranciers->hasMorePages())
                            <a href="{{ $leveranciers->nextPageUrl() }}" class="px-3 py-1 bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 rounded">Volgende</a>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 border border-gray-300 rounded cursor-not-allowed">Volgende</span>
                        @endif
                    </div>
                </div>

                {{-- Knoppen onderaan (Home rechts uitgelijnd volgens Wireframe-02) --}}
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
