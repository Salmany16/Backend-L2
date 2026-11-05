<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Leverancier extends Model
{
    use HasFactory;

    protected $table = 'Leverancier';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'Naam',
        'ContactPersoon',
        'LeverancierNummer',
        'Mobiel',
        'IsActief',
        'Opmerking',
    ];

    protected $casts = [
        'IsActief' => 'boolean',
    ];

    public function productPerLeveranciers(): HasMany
    {
        return $this->hasMany(ProductPerLeverancier::class, 'LeverancierId', 'Id');
    }

    /**
     * Haalt overzicht op van leveranciers met aantal verschillende producten via Eloquent Models.
     */
    public static function getOverzicht(): Collection
    {
        return self::with('productPerLeveranciers')
            ->get()
            ->map(function ($leverancier) {
                return (object) [
                    'Id' => $leverancier->Id,
                    'Naam' => $leverancier->Naam,
                    'ContactPersoon' => $leverancier->ContactPersoon,
                    'LeverancierNummer' => $leverancier->LeverancierNummer,
                    'Mobiel' => $leverancier->Mobiel,
                    'AantalVerschillendeProducten' => $leverancier->productPerLeveranciers->pluck('ProductId')->unique()->count(),
                ];
            })
            ->sortByDesc('AantalVerschillendeProducten')
            ->values();
    }

    /**
     * Haalt de geleverde producten per leverancier op via Eloquent Models.
     */
    public static function getGeleverdeProducten(int $leverancierId): Collection
    {
        $leveringen = ProductPerLeverancier::with(['product.magazijn'])
            ->where('LeverancierId', $leverancierId)
            ->get();

        return $leveringen->groupBy('ProductId')
            ->map(function ($groep) {
                $eerste = $groep->first();
                $product = $eerste->product;
                $magazijn = $product?->magazijn;

                return (object) [
                    'ProductId' => $product?->Id,
                    'ProductNaam' => $product?->Naam,
                    'IsActief' => (bool) $product?->IsActief,
                    'VerpakkingsEenheid' => $magazijn?->VerpakkingsEenheid,
                    'AantalAanwezig' => $magazijn?->AantalAanwezig,
                    'LaatsteLevering' => $groep->max('DatumLevering'),
                ];
            })
            ->sortByDesc('AantalAanwezig')
            ->values();
    }

    /**
     * Voegt een nieuwe levering toe en hoogt de magazijnvoorraad op via Eloquent Models.
     */
    public static function slaLeveringOp(int $leverancierId, int $productId, int $aantal, string $datumEerstVolgende): void
    {
        ProductPerLeverancier::create([
            'LeverancierId' => $leverancierId,
            'ProductId' => $productId,
            'DatumLevering' => now()->toDateString(),
            'Aantal' => $aantal,
            'DatumEerstVolgendeLevering' => $datumEerstVolgende,
            'IsActief' => true,
        ]);

        $magazijn = Magazijn::where('ProductId', $productId)->first();
        if ($magazijn) {
            $magazijn->AantalAanwezig = ($magazijn->AantalAanwezig ?? 0) + $aantal;
            $magazijn->save();
        }
    }
}
