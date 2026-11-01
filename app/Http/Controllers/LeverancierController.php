<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * LeverancierController
 *
 * Handles leverancier overview and delivery registration.
 *
 * NOTE: In a MySQL production environment the SELECT queries below would be
 * encapsulated as stored procedures and called via:
 *   DB::select('CALL sp_GetLeveranciersOverzicht()')
 *   DB::select('CALL sp_GetGeleverdeProdPerLeverancier(?)', [$leverancierId])
 *   DB::statement('CALL sp_SlaLeveringOp(?, ?, ?, ?)', [...])
 *
 * Because this project uses SQLite (which does not support stored procedures),
 * equivalent raw SQL is executed through PDO-style bindings instead.
 */
class LeverancierController extends Controller
{
    /**
     * Wireframe-01: Overzicht leveranciers gesorteerd op aantal verschillende producten DESC.
     */
    public function index()
    {
        // Equivalent of sp_GetLeveranciersOverzicht
        $leveranciers = DB::select('
            SELECT l.Id,
                   l.Naam,
                   l.ContactPersoon,
                   l.LeverancierNummer,
                   l.Mobiel,
                   COUNT(DISTINCT ppl.ProductId) as AantalVerschillendeProducten
            FROM Leverancier l
            LEFT JOIN ProductPerLeverancier ppl ON l.Id = ppl.LeverancierId
            GROUP BY l.Id, l.Naam, l.ContactPersoon, l.LeverancierNummer, l.Mobiel
            ORDER BY AantalVerschillendeProducten DESC
        ');

        return view('leverancier.index', [
            'leveranciers' => $leveranciers,
        ]);
    }

    /**
     * Wireframe-02 / Wireframe-03: Geleverde producten per leverancier.
     */
    public function showProducten(int $leverancierId)
    {
        $leverancier = DB::table('Leverancier')->where('Id', $leverancierId)->first();

        // Equivalent of sp_GetGeleverdeProdPerLeverancier
        $producten = DB::select('
            SELECT p.Id as ProductId,
                   p.Naam as ProductNaam,
                   p.IsActief,
                   m.VerpakkingsEenheid,
                   m.AantalAanwezig,
                   MAX(ppl.DatumLevering) as LaatsteLevering
            FROM ProductPerLeverancier ppl
            JOIN Product p ON ppl.ProductId = p.Id
            LEFT JOIN Magazijn m ON p.Id = m.ProductId
            WHERE ppl.LeverancierId = ?
            GROUP BY p.Id, p.Naam, p.IsActief, m.VerpakkingsEenheid, m.AantalAanwezig
            ORDER BY m.AantalAanwezig DESC
        ', [$leverancierId]);

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
        $leverancier = DB::table('Leverancier')->where('Id', $leverancierId)->first();
        $product = DB::table('Product')->where('Id', $productId)->first();

        return view('leverancier.levering-form', [
            'leverancier' => $leverancier,
            'product' => $product,
            'errorMelding' => null,
        ]);
    }

    /**
     * POST handler: Sla een nieuwe levering op.
     * Equivalent of sp_SlaLeveringOp in MySQL.
     */
    public function slaLeveringOp(Request $request, int $leverancierId, int $productId)
    {
        $request->validate([
            'aantal' => ['required', 'integer', 'min:1'],
            'datum_eerstvolgende' => ['required', 'date'],
        ]);

        $leverancier = DB::table('Leverancier')->where('Id', $leverancierId)->first();
        $product = DB::table('Product')->where('Id', $productId)->first();

        // Controleer of product actief is
        if ((int) $product->IsActief === 0) {
            $errorMelding = "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd";

            return view('leverancier.levering-form', [
                'leverancier' => $leverancier,
                'product' => $product,
                'errorMelding' => $errorMelding,
            ]);
        }

        $aantal = (int) $request->input('aantal');
        $datumEerstVolgende = $request->input('datum_eerstvolgende');
        $datumLevering = now()->toDateString();

        // Voeg nieuwe leveringsregel in
        DB::insert(
            'INSERT INTO ProductPerLeverancier (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering, IsActief, DatumAangemaakt, DatumGewijzigd)
             VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
            [$leverancierId, $productId, $datumLevering, $aantal, $datumEerstVolgende]
        );

        // Bijwerken van voorraad in Magazijn
        DB::update(
            'UPDATE Magazijn SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + ?, DatumGewijzigd = CURRENT_TIMESTAMP WHERE ProductId = ?',
            [$aantal, $productId]
        );

        return redirect()->route('leverancier.producten', $leverancierId);
    }
}
