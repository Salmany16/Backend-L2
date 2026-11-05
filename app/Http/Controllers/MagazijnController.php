<?php

namespace App\Http\Controllers;

use App\Models\Product;

class MagazijnController extends Controller
{
    public function index()
    {
        // Haalt producten op via Stored Procedure sp_GetMagazijnOverzicht (aangeroepen via Model Product en PDO)
        $producten = Product::getMagazijnOverzicht();

        return view('magazijn.index', [
            'producten' => $producten,
        ]);
    }
}
