<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-normal mb-4">
                        <u>Homepagina</u>
                    </h1>

                    <p class="text-base text-gray-700 mb-6">Je bent ingelogd als: <span class="font-semibold">{{ $rol }}</span></p>

                    <div class="mb-6">
                        <a href="{{ route('leverancier.wijzigen.index') }}" class="text-xl text-blue-600 hover:text-blue-800 underline font-medium">
                            Wijzigen Leveranciers
                        </a>
                    </div>

                    <hr class="my-6 border-gray-300">

                    <p class="text-sm text-gray-600 mb-2">Overige modules:</p>
                    <div class="flex gap-3">
                        <a href="{{ route('magazijn.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                            Overzicht Magazijn Jamin
                        </a>
                        <a href="{{ route('leverancier.index') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded">
                            Overzicht Leveranciers (Opdracht 02)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
