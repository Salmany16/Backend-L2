<?php

namespace App\Http\Controllers;

use App\Models\Levering;
use App\Models\Product;
use Illuminate\Http\Request;

class LeveringController extends Controller
{
    public function show(int $productId)
    {
        $product = Product::getById($productId);
        abort_if(! $product, 404);

        $leveringen = collect(Levering::getLeveringDetails($productId));
        $leverancier = $leveringen->first();

        $heeftVoorraad = ! ($product->AantalAanwezig === null || $product->AantalAanwezig == 0);
        $geenVoorraadMelding = '';

        if (! $heeftVoorraad) {
            $leveringMetVolgende = $leveringen->whereNotNull('DatumEerstVolgendeLevering')->first() ?? $leverancier;
            $datumStr = $leveringMetVolgende?->DatumEerstVolgendeLevering
                ? date('d-m-Y', strtotime($leveringMetVolgende->DatumEerstVolgendeLevering))
                : 'onbekend';
            $geenVoorraadMelding = "Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: {$datumStr}";
        }

        return view('levering.show', [
            'product' => $product,
            'magazijn' => $product,
            'leveringen' => $leveringen,
            'leverancier' => $leverancier,
            'heeftVoorraad' => $heeftVoorraad,
            'geenVoorraadMelding' => $geenVoorraadMelding,
        ]);
    }

    public function create(int $leverancierId, int $productId)
    {
        $leveringInfo = Levering::getProductLeveringDetails($leverancierId, $productId);
        if (! $leveringInfo) {
            return redirect()->route('leverancier.index');
        }

        return view('levering.create', [
            'leveringInfo' => $leveringInfo,
            'leverancierId' => $leverancierId,
            'productId' => $productId,
        ]);
    }

    public function store(Request $request, int $leverancierId, int $productId)
    {
        $request->validate([
            'aantal' => 'required|integer|min:1',
            'datum_eerstvolgende_levering' => 'nullable|date',
        ]);

        $leveringInfo = Levering::getProductLeveringDetails($leverancierId, $productId);
        if (! $leveringInfo) {
            return redirect()->route('leverancier.index');
        }

        if (! $leveringInfo->ProductIsActief) {
            return redirect()
                ->route('levering.create', ['leverancierId' => $leverancierId, 'productId' => $productId])
                ->with('inactief_melding', "Het product {$leveringInfo->ProductNaam} van de leverancier {$leveringInfo->LeverancierNaam} wordt niet meer geproduceerd");
        }

        Levering::voegLeveringToe(
            $leverancierId,
            $productId,
            (int) $request->input('aantal'),
            $request->input('datum_eerstvolgende_levering')
        );

        return redirect()->route('leverancier.producten', $leverancierId);
    }
}
