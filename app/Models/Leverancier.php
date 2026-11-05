<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
     * Stored Procedure: sp_GetLeveranciersOverzicht
     */
    public static function getOverzicht(): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return DB::select('
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
        }

        return DB::select('CALL sp_GetLeveranciersOverzicht()');
    }

    /**
     * Stored Procedure: sp_GetGeleverdeProdPerLeverancier
     */
    public static function getGeleverdeProducten(int $leverancierId): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return DB::select('
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
        }

        return DB::select('CALL sp_GetGeleverdeProdPerLeverancier(?)', [$leverancierId]);
    }

    /**
     * Stored Procedure: sp_SlaLeveringOp
     */
    public static function slaLeveringOp(int $leverancierId, int $productId, int $aantal, string $datumEerstVolgende): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::insert(
                'INSERT INTO ProductPerLeverancier (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering, IsActief, DatumAangemaakt, DatumGewijzigd)
                 VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
                [$leverancierId, $productId, now()->toDateString(), $aantal, $datumEerstVolgende]
            );

            DB::update(
                'UPDATE Magazijn SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + ?, DatumGewijzigd = CURRENT_TIMESTAMP WHERE ProductId = ?',
                [$aantal, $productId]
            );

            return;
        }

        DB::statement('CALL sp_SlaLeveringOp(?, ?, ?, ?)', [
            $leverancierId,
            $productId,
            $aantal,
            $datumEerstVolgende,
        ]);
    }
}
