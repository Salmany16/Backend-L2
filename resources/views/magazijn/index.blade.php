<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-normal">
                        <u>Overzicht Magazijn Jamin</u>
                    </h1>
                    <a href="{{ route('leverancier.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm">
                        Ga naar Overzicht Leveranciers ➔
                    </a>
                </div>

                <table class="w-full border-collapse border border-black text-left text-base">
                    <thead>
                        <tr>
                            <th class="border border-black p-2 font-normal">Barcode</th>
                            <th class="border border-black p-2 font-normal">Naam</th>
                            <th class="border border-black p-2 font-normal">Verpakkingseenheid</th>
                            <th class="border border-black p-2 font-normal">Aantalaanwezig</th>
                            <th class="border border-black p-2 text-center font-normal">Allergenen Info</th>
                            <th class="border border-black p-2 text-center font-normal">Leverantie Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producten as $product)
                            <tr>
                                <td class="border border-black p-2">{{ $product->Barcode }}</td>
                                <td class="border border-black p-2">{{ $product->Naam }}</td>
                                <td class="border border-black p-2">{{ str_replace('.', ',', (string)$product->VerpakkingsEenheid) }}</td>
                                <td class="border border-black p-2">{{ $product->AantalAanwezig }}</td>
                                <td class="border border-black p-2 text-center">
                                    <a href="{{ route('allergeen.show', $product->Id) }}" class="text-red-600 font-bold text-xl no-underline">
                                        X
                                    </a>
                                </td>
                                <td class="border border-black p-2 text-center">
                                    <a href="{{ route('levering.show', $product->Id) }}" class="text-blue-600 font-bold text-xl no-underline">
                                        ?
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
