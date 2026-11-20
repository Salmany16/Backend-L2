<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6">

                <h1 class="text-2xl font-normal mb-6">
                    <u>Leverancier Details</u>
                </h1>

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <table class="w-full max-w-2xl border-collapse border border-black text-left text-base mb-6">
                    <tbody>
                        <tr>
                            <td class="border border-black p-2 font-medium w-1/3">Naam</td>
                            <td class="border border-black p-2">{{ $leverancier->Naam }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Contactpersoon</td>
                            <td class="border border-black p-2">{{ $leverancier->ContactPersoon }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Leveranciernummer</td>
                            <td class="border border-black p-2">{{ $leverancier->LeverancierNummer }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Mobiel</td>
                            <td class="border border-black p-2">{{ $leverancier->Mobiel }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Straatnaam</td>
                            <td class="border border-black p-2">{{ $leverancier->Straatnaam ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Huisnummer</td>
                            <td class="border border-black p-2">{{ $leverancier->Huisnummer ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Postcode</td>
                            <td class="border border-black p-2">{{ $leverancier->Postcode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black p-2 font-medium">Stad</td>
                            <td class="border border-black p-2">{{ $leverancier->Stad ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-between items-center max-w-2xl mt-6">
                    <a href="{{ route('leverancier.edit', $leverancier->LeverancierId) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded">
                        Wijzig
                    </a>
                    <div class="flex gap-3">
                        <a href="{{ route('leverancier.wijzigen.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            Terug
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                            Home
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
