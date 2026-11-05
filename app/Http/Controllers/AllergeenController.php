<?php

namespace App\Http\Controllers;

use App\Models\Allergeen;
use App\Models\Product;

class AllergeenController extends Controller
{
    public function show(int $productId)
    {
        $product = Product::getById($productId);
        abort_if(! $product, 404);

        $allergenen = Allergeen::getAllergenenVoorProduct($productId);
        $heeftAllergenen = count($allergenen) > 0;

        return view('allergeen.show', [
            'product' => $product,
            'allergenen' => $allergenen,
            'heeftAllergenen' => $heeftAllergenen,
            'geenAllergenenMelding' => $heeftAllergenen ? '' : 'In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken',
        ]);
    }
}
