<?php

namespace App\Http\Controllers;

use App\Models\Product;

class MagazijnController extends Controller
{
    public function index()
    {
        return view('magazijn.index', [
            'producten' => Product::getMagazijnOverzicht(),
        ]);
    }
}
