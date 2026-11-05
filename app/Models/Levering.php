<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use PDO;

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
     * Stored Procedure: sp_GetLeveringDetails via PDO
     */
    public static function getLeveringDetails(int $productId): array
    {
        $pdo = DB::connection()->getPdo();

        if (DB::connection()->getDriverName() === 'mysql') {
            $stmt = $pdo->prepare('CALL sp_GetLeveringDetails(:productId)');
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();

            return $results;
        }

        // SQLite fallback via PDO prepare
        $stmt = $pdo->prepare('
            SELECT ppl.Id,
                   ppl.LeverancierId,
                   ppl.ProductId,
                   ppl.DatumLevering,
                   ppl.Aantal,
                   ppl.DatumEerstVolgendeLevering,
                   l.Naam AS LeverancierNaam,
                   l.ContactPersoon,
                   l.LeverancierNummer,
                   l.Mobiel
            FROM ProductPerLeverancier ppl
            JOIN Leverancier l ON ppl.LeverancierId = l.Id
            WHERE ppl.ProductId = :productId
            ORDER BY ppl.DatumLevering ASC
        ');
        $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
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
     * Stored Procedure: sp_SlaLeveringOp via PDO
     * Voegt een nieuwe levering toe en verhoogt het aantal in het magazijn.
     */
    public static function voegLeveringToe(int $leverancierId, int $productId, int $aantal, ?string $datumEerstvolgende = null): void
    {
        Leverancier::slaLeveringOp($leverancierId, $productId, $aantal, $datumEerstvolgende ?? '');
    }
}
