<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * LeverancierController (Controller in MVC)
 *
 * Coördineert de verzoeken voor het leveranciersoverzicht en levering registraties.
 * Maakt gebruik van de Models Leverancier en Product voor alle data-opvragingen (OOP & MVC).
 */
class LeverancierController extends Controller
{
    /**
     * Wireframe-01: Overzicht leveranciers gesorteerd op aantal verschillende producten DESC.
     */
    public function index()
    {
        // Model roept de stored procedure / query aan (sp_GetLeveranciersOverzicht)
        $leveranciers = Leverancier::getOverzicht();

        return view('leverancier.index', [
            'leveranciers' => $leveranciers,
        ]);
    }

    /**
     * Wireframe-02 / Wireframe-03: Geleverde producten per leverancier.
     */
    public function showProducten(int $leverancierId)
    {
        $leverancier = Leverancier::findOrFail($leverancierId);

        // Model roept de stored procedure / query aan (sp_GetGeleverdeProdPerLeverancier)
        $producten = Leverancier::getGeleverdeProducten($leverancierId);
        $heeftProducten = count($producten) > 0;

        return view('leverancier.producten', [
            'leverancier' => $leverancier,
            'producten' => $producten,
            'heeftProducten' => $heeftProducten,
        ]);
    }

    /**
     * Wireframe-04: Formulier nieuwe levering toevoegen.
     */
    public function showLeveringForm(int $leverancierId, int $productId)
    {
        $leverancier = Leverancier::findOrFail($leverancierId);
        $product = Product::findOrFail($productId);

        return view('leverancier.levering-form', [
            'leverancier' => $leverancier,
            'product' => $product,
            'errorMelding' => null,
        ]);
    }

    /**
     * POST handler: Sla een nieuwe levering op via het Model.
     */
    public function slaLeveringOp(Request $request, int $leverancierId, int $productId)
    {
        $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
            'datum_eerstvolgende' => ['required', 'date'],
        ]);

        $leverancier = Leverancier::findOrFail($leverancierId);
        $product = Product::findOrFail($productId);

        // Controleer of product actief is (User Story 02 - Scenario 02)
        if (! $product->IsActief) {
            $errorMelding = "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd";

            return view('leverancier.levering-form', [
                'leverancier' => $leverancier,
                'product' => $product,
                'errorMelding' => $errorMelding,
            ]);
        }

        $aantal = (int) $request->input('aantal');
        $datumEerstVolgende = $request->input('datum_eerstvolgende');

        // Model voert de database-transactie uit (sp_SlaLeveringOp)
        Leverancier::slaLeveringOp($leverancierId, $productId, $aantal, $datumEerstVolgende);

        return redirect()->route('leverancier.producten', $leverancierId);
    }
}
