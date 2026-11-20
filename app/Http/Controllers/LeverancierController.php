<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LeverancierController extends Controller
{
    /**
     * BE-opdracht 02: Overzicht leveranciers gesorteerd op aantal verschillende producten DESC.
     */
    public function index()
    {
        return view('leverancier.index', [
            'leveranciers' => Leverancier::getOverzicht(),
        ]);
    }

    /**
     * BE-opdracht 02: Geleverde producten per leverancier.
     */
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

    /**
     * BE-opdracht 02: Leveringsformulier.
     */
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

    /**
     * BE-opdracht 02: POST levering opslaan.
     */
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

    /**
     * BE-opdracht 03 - Wireframe-02: Overzicht leveranciers met server-side pagination (max 4 records per pagina).
     */
    public function indexWijzigen(Request $request)
    {
        $perPage = 4;
        $page = max((int) $request->input('page', 1), 1);
        $offset = ($page - 1) * $perPage;

        $leveranciersData = Leverancier::getOverzichtPaginering($offset, $perPage);
        $total = Leverancier::getCount();

        $leveranciers = new LengthAwarePaginator(
            $leveranciersData,
            $total,
            $perPage,
            $page,
            ['path' => route('leverancier.wijzigen.index'), 'query' => $request->query()]
        );

        return view('leverancier.wijzigen-overzicht', [
            'leveranciers' => $leveranciers,
        ]);
    }

    /**
     * BE-opdracht 03 - Wireframe-03: Leverancier Details.
     */
    public function showDetails(int $leverancierId)
    {
        $leverancier = Leverancier::getDetailsEnAdres($leverancierId);
        abort_if(! $leverancier, 404);

        return view('leverancier.details', [
            'leverancier' => $leverancier,
        ]);
    }

    /**
     * BE-opdracht 03 - Wireframe-04: Wijzig Leveranciergegevens formulier.
     */
    public function edit(int $leverancierId)
    {
        $leverancier = Leverancier::getDetailsEnAdres($leverancierId);
        abort_if(! $leverancier, 404);

        return view('leverancier.wijzigen-form', [
            'leverancier' => $leverancier,
            'successMelding' => null,
        ]);
    }

    /**
     * BE-opdracht 03: POST handler voor wijzigen leveranciergegevens en contact.
     */
    public function update(Request $request, int $leverancierId)
    {
        $request->validate([
            'naam' => ['required', 'string', 'max:100'],
            'contactpersoon' => ['required', 'string', 'max:100'],
            'leveranciernummer' => ['required', 'string', 'max:20'],
            'mobiel' => ['required', 'string', 'max:20'],
            'straatnaam' => ['required', 'string', 'max:100'],
            'huisnummer' => ['required', 'integer'],
            'postcode' => ['required', 'string', 'max:10'],
            'stad' => ['required', 'string', 'max:100'],
        ]);

        $leverancier = Leverancier::getDetailsEnAdres($leverancierId);
        abort_if(! $leverancier, 404);

        Leverancier::updateEnContact(
            $leverancierId,
            $request->input('naam'),
            $request->input('contactpersoon'),
            $request->input('leveranciernummer'),
            $request->input('mobiel'),
            $request->input('straatnaam'),
            (int) $request->input('huisnummer'),
            $request->input('postcode'),
            $request->input('stad')
        );

        $bijgewerkteLeverancier = Leverancier::getDetailsEnAdres($leverancierId);

        return view('leverancier.wijzigen-form', [
            'leverancier' => $bijgewerkteLeverancier,
            'successMelding' => 'De wijzigingen zijn doorgevoerd',
        ]);
    }
}
