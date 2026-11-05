<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Levering extends Model
{
    use HasFactory;

    protected $table = 'ProductPerLeverancier';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'LeverancierId',
        'ProductId',
        'DatumLevering',
        'Aantal',
        'DatumEerstVolgendeLevering',
        'IsActief',
        'Opmerking',
    ];

    protected $casts = [
        'DatumLevering' => 'date',
        'DatumEerstVolgendeLevering' => 'date',
        'Aantal' => 'integer',
        'IsActief' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }

    public function leverancier(): BelongsTo
    {
        return $this->belongsTo(Leverancier::class, 'LeverancierId', 'Id');
    }

    /**
     * Haalt de details op van een product en leverancier voor het leveringsformulier (Wireframe-04).
     */
    public static function getProductLeveringDetails(int $leverancierId, int $productId): ?object
    {
        $product = Product::find($productId);
        $leverancier = Leverancier::find($leverancierId);

        if (! $product || ! $leverancier) {
            return null;
        }

        return (object) [
            'ProductId' => $product->Id,
            'ProductNaam' => $product->Naam,
            'ProductIsActief' => (bool) $product->IsActief,
            'LeverancierId' => $leverancier->Id,
            'LeverancierNaam' => $leverancier->Naam,
            'ContactPersoon' => $leverancier->ContactPersoon,
            'Mobiel' => $leverancier->Mobiel,
        ];
    }

    /**
     * Voegt een nieuwe levering toe en verhoogt het aantal in het magazijn.
     */
    public static function voegLeveringToe(int $leverancierId, int $productId, int $aantal, ?string $datumEerstvolgende = null): void
    {
        self::create([
            'LeverancierId' => $leverancierId,
            'ProductId' => $productId,
            'DatumLevering' => now()->toDateString(),
            'Aantal' => $aantal,
            'DatumEerstVolgendeLevering' => $datumEerstvolgende,
            'IsActief' => true,
        ]);

        $magazijn = Magazijn::where('ProductId', $productId)->first();
        if ($magazijn) {
            $magazijn->AantalAanwezig = ($magazijn->AantalAanwezig ?? 0) + $aantal;
            $magazijn->save();
        }
    }
}
