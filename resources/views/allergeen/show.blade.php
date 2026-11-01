<x-app-layout>
    @if (!$heeftAllergenen)
        <x-slot name="head">
            <meta http-equiv="refresh" content="4; url={{ route('magazijn.index') }}">
        </x-slot>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200/80 p-6">

                <h1 class="sr-only">Overzicht Allergenen</h1>

                <div class="mb-5 space-y-1 text-sm md:text-base text-gray-900">
                    <p>Naam: {{ $product->Naam }}</p>
                    <p>Barcode: {{ $product->Barcode }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200 text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border border-gray-200 px-4 py-2.5 font-bold text-gray-900 w-1/3">Naam</th>
                                <th class="border border-gray-200 px-4 py-2.5 font-bold text-gray-900 w-2/3">Omschrijving</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($heeftAllergenen)
                                @foreach ($allergenen as $allergeen)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border border-gray-200 px-4 py-2 text-gray-900">{{ $allergeen->Naam }}</td>
                                        <td class="border border-gray-200 px-4 py-2 text-gray-900">{{ $allergeen->Omschrijving }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="border border-gray-200 py-3.5 px-4 text-center italic text-gray-700">
                                        {{ $geenAllergenenMelding }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('magazijn.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Terug
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        Home
                    </a>
                </div>

                @if (!$heeftAllergenen)
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

