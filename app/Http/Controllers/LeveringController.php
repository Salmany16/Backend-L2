<?php

namespace App\Http\Controllers;

use App\Models\Levering;
use App\Models\Product;
use App\Models\ProductPerLeverancier;
use Illuminate\Http\Request;

class LeveringController extends Controller
{
    public function show($productId)
    {
        // Product en Magazijn voorraad ophalen via het Model Product (MVC & OOP)
        $product = Product::with('magazijn')->findOrFail($productId);
        $magazijn = $product->magazijn;

        // Leveringen en leverancier ophalen via het Model ProductPerLeverancier met relatie
        $leveringen = ProductPerLeverancier::with('leverancier')
            ->where('ProductId', $productId)
            ->orderBy('DatumLevering', 'asc')
            ->get()
            ->map(function ($levering) {
                return (object) [
                    'Id' => $levering->Id,
                    'LeverancierId' => $levering->LeverancierId,
                    'ProductId' => $levering->ProductId,
                    'DatumLevering' => $levering->DatumLevering,
                    'Aantal' => $levering->Aantal,
                    'DatumEerstVolgendeLevering' => $levering->DatumEerstVolgendeLevering,
                    'LeverancierNaam' => $levering->leverancier?->Naam,
                    'ContactPersoon' => $levering->leverancier?->ContactPersoon,
                    'LeverancierNummer' => $levering->leverancier?->LeverancierNummer,
                    'Mobiel' => $levering->leverancier?->Mobiel,
                ];
            });

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

    /**
     * Detailscherm Levering product (Wireframe-04).
     */
    public function create($leverancierId, $productId)
    {
        // Haal product- en leveranciergegevens op via Model Levering (MVC)
        $leveringInfo = Levering::getProductLeveringDetails((int) $leverancierId, (int) $productId);

        if (! $leveringInfo) {
            return redirect()->route('leverancier.index');
        }

        return view('levering.create', [
            'leveringInfo' => $leveringInfo,
            'leverancierId' => $leverancierId,
            'productId' => $productId,
        ]);
    }

    /**
     * Verwerkt de levering van een product.
     * Scenario 01: Verhoogt de voorraad en legt levering vast.
     * Scenario 02: Als product niet actief is (IsActief == false), toon melding en stuur na 4 sec door.
     */
    public function store(Request $request, $leverancierId, $productId)
    {
        $request->validate([
            'aantal' => 'required|integer|min:1',
            'datum_eerstvolgende_levering' => 'nullable|date',
        ]);

        // Haal product- en leveranciergegevens op via Model Levering
        $leveringInfo = Levering::getProductLeveringDetails((int) $leverancierId, (int) $productId);

        if (! $leveringInfo) {
            return redirect()->route('leverancier.index');
        }

        // Scenario 02: Product is niet actief (Winegums van Basset)
        if (! $leveringInfo->ProductIsActief) {
            return redirect()
                ->route('levering.create', ['leverancierId' => $leverancierId, 'productId' => $productId])
                ->with('inactief_melding', "Het product {$leveringInfo->ProductNaam} van de leverancier {$leveringInfo->LeverancierNaam} wordt niet meer geproduceerd");
        }

        // Scenario 01: Product is actief -> toevoegen via Model Levering (MVC)
        $aantal = (int) $request->input('aantal');
        $datumEerstvolgende = $request->input('datum_eerstvolgende_levering') ?: null;

        Levering::voegLeveringToe(
            (int) $leverancierId,
            (int) $productId,
            $aantal,
            $datumEerstvolgende
        );

        return redirect()->route('leverancier.producten', $leverancierId);
    }
}
