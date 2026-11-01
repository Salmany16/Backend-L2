<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LeveringController extends Controller
{
    public function show($productId)
    {
        // Product ophalen
        $product = DB::table('Product')->where('Id', $productId)->first();

        // Magazijn voorraad ophalen
        $magazijn = DB::table('Magazijn')->where('ProductId', $productId)->first();

        // Leveringen en leverancier ophalen
        $leveringen = DB::table('ProductPerLeverancier')
            ->join('Leverancier', 'ProductPerLeverancier.LeverancierId', '=', 'Leverancier.Id')
            ->where('ProductPerLeverancier.ProductId', $productId)
            ->select(
                'ProductPerLeverancier.*',
                'Leverancier.Naam as LeverancierNaam',
                'Leverancier.ContactPersoon',
                'Leverancier.LeverancierNummer',
                'Leverancier.Mobiel'
            )
            ->orderBy('ProductPerLeverancier.DatumLevering', 'asc')
            ->get();

        $leverancier = $leveringen->first();

        // Controleren of er voorraad is
        $heeftVoorraad = true;
        $geenVoorraadMelding = '';

        if (! $magazijn || $magazijn->AantalAanwezig === null || $magazijn->AantalAanwezig == 0) {
            $heeftVoorraad = false;

            $leveringMetVolgende = $leveringen->whereNotNull('DatumEerstVolgendeLevering')->first()
                ?? $leveringen->first();

            $datumStr = $leveringMetVolgende?->DatumEerstVolgendeLevering
                ? date('d-m-Y', strtotime($leveringMetVolgende->DatumEerstVolgendeLevering))
                : 'onbekend';

            $geenVoorraadMelding = "Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: {$datumStr}";
        }

        return view('levering.show', [
            'product' => $product,
            'magazijn' => $magazijn,
            'leveringen' => $leveringen,
            'leverancier' => $leverancier,
            'heeftVoorraad' => $heeftVoorraad,
            'geenVoorraadMelding' => $geenVoorraadMelding,
        ]);
    }
}
