<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Models\Product;
use Illuminate\Http\Request;

class LeverancierController extends Controller
{
    public function index()
    {
        return view('leverancier.index', [
            'leveranciers' => Leverancier::getOverzicht(),
        ]);
    }

    public function showProducten(int $leverancierId)
    {
        $leverancier = Leverancier::getById($leverancierId);
        abort_if(! $leverancier, 404);

        $producten = Leverancier::getGeleverdeProducten($leverancierId);

        return view('leverancier.producten', [
            'leverancier' => $leverancier,
            'producten' => $producten,
            'heeftProducten' => count($producten) > 0,
        ]);
    }

    public function showLeveringForm(int $leverancierId, int $productId)
    {
        $leverancier = Leverancier::getById($leverancierId);
        $product = Product::getById($productId);
        abort_if(! $leverancier || ! $product, 404);

        return view('leverancier.levering-form', [
            'leverancier' => $leverancier,
            'product' => $product,
            'errorMelding' => null,
        ]);
    }

    public function slaLeveringOp(Request $request, int $leverancierId, int $productId)
    {
        $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
            'datum_eerstvolgende' => ['required', 'date'],
        ]);

        $leverancier = Leverancier::getById($leverancierId);
        $product = Product::getById($productId);
        abort_if(! $leverancier || ! $product, 404);

        if (! $product->IsActief) {
            return view('leverancier.levering-form', [
                'leverancier' => $leverancier,
                'product' => $product,
                'errorMelding' => "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd",
            ]);
        }

        Leverancier::slaLeveringOp($leverancierId, $productId, (int) $request->input('aantal'), $request->input('datum_eerstvolgende'));

        return redirect()->route('leverancier.producten', $leverancierId);
    }
}
