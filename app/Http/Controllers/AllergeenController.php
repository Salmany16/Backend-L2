<?php

namespace App\Http\Controllers;

use App\Models\Allergeen;
use App\Models\Product;

class AllergeenController extends Controller
{
    public function show($productId)
    {
        // Product ophalen via het Model Product (MVC & OOP)
        $product = Product::findOrFail($productId);

        // Allergenen ophalen via het Model Allergeen en koppeltabel relatie
        $allergenen = Allergeen::whereHas('productPerAllergenen', function ($query) use ($productId) {
            $query->where('ProductId', $productId);
        })
            ->orderBy('Naam', 'asc')
            ->get();

        // Controleren of er allergenen zijn
        $heeftAllergenen = $allergenen->isNotEmpty();
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
