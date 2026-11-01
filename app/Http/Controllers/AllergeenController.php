<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AllergeenController extends Controller
{
    public function show($productId)
    {
        // Product ophalen
        $product = DB::table('Product')->where('Id', $productId)->first();

        // Allergenen ophalen van dit product
        $allergenen = DB::table('ProductPerAllergeen')
            ->join('Allergeen', 'ProductPerAllergeen.AllergeenId', '=', 'Allergeen.Id')
            ->where('ProductPerAllergeen.ProductId', $productId)
            ->select('Allergeen.Naam', 'Allergeen.Omschrijving')
            ->orderBy('Allergeen.Naam', 'asc')
            ->get();

        // Controleren of er allergenen zijn
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
