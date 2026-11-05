<?php

namespace App\Http\Controllers;

use App\Models\Allergeen;
use App\Models\Product;

class AllergeenController extends Controller
{
    public function show($productId)
    {
        // Product ophalen via Model Product
        $product = Product::findOrFail($productId);

        // Allergenen ophalen via Stored Procedure sp_GetAllergenenPerProduct (via Model Allergeen en PDO)
        $allergenen = Allergeen::getAllergenenVoorProduct((int) $productId);

        $heeftAllergenen = count($allergenen) > 0;
        $geenAllergenenMelding = '';

        if (! $heeftAllergenen) {
            $geenAllergenenMelding = 'In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken';
        }

        return view('allergeen.show', [
            'product' => $product,
            'allergenen' => $allergenen,
            'heeftAllergenen' => $heeftAllergenen,
            'geenAllergenenMelding' => $geenAllergenenMelding,
        ]);
    }
}
