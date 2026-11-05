<?php

namespace App\Http\Controllers;

use App\Models\Levering;
use App\Models\Product;
use Illuminate\Http\Request;

class LeveringController extends Controller
{
    public function show($productId)
    {
        // Product en Magazijn voorraad ophalen via Model Product
        $product = Product::with('magazijn')->findOrFail($productId);
        $magazijn = $product->magazijn;

        // Leveringen en leverancier ophalen via Stored Procedure sp_GetLeveringDetails (Model Levering & PDO)
        $leveringen = collect(Levering::getLeveringDetails((int) $productId));
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
        // Haal product- en leveranciergegevens op via Model Levering (MVC & OOP)
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
     * Verwerkt de levering van een product via Stored Procedure sp_SlaLeveringOp.
     */
    public function store(Request $request, $leverancierId, $productId)
    {
        $request->validate([
            'aantal' => 'required|integer|min:1',
            'datum_eerstvolgende_levering' => 'nullable|date',
        ]);

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

        // Scenario 01: Product is actief -> toevoegen via Stored Procedure (Model Levering & PDO)
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
