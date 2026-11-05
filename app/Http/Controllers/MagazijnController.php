<?php

namespace App\Http\Controllers;

use App\Models\Product;

class MagazijnController extends Controller
{
    public function index()
    {
        // Haal alle actieve producten op via het Model Product met de Magazijn relatie (MVC & OOP)
        $producten = Product::where('IsActief', 1)
            ->with('magazijn')
            ->orderBy('Barcode', 'asc')
            ->get()
            ->map(function ($product) {
                return (object) [
                    'Id' => $product->Id,
                    'Naam' => $product->Naam,
                    'Barcode' => $product->Barcode,
                    'VerpakkingsEenheid' => $product->magazijn?->VerpakkingsEenheid,
                    'AantalAanwezig' => $product->magazijn?->AantalAanwezig,
                ];
            });

        return view('magazijn.index', [
            'producten' => $producten,
        ]);
    }
}
