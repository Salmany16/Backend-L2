<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MagazijnController extends Controller
{
    public function index()
    {
        // Haal alle producten op met de magazijn voorraad
        $producten = DB::table('Product')
            ->leftJoin('Magazijn', 'Product.Id', '=', 'Magazijn.ProductId')
            ->select(
                'Product.Id',
                'Product.Naam',
                'Product.Barcode',
                'Magazijn.VerpakkingsEenheid',
                'Magazijn.AantalAanwezig'
            )
            ->where('Product.IsActief', 1)
            ->orderBy('Product.Barcode', 'asc')
            ->get();

        return view('magazijn.index', [
            'producten' => $producten,
        ]);
    }
}
