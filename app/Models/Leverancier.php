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
     * Stored Procedure equivalent: sp_GetLeveranciersOverzicht
     * Haalt overzicht op van leveranciers met aantal verschillende producten gesorteerd aflopend.
     */
    public static function getOverzicht(): array
    {
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

    /**
     * Stored Procedure equivalent: sp_GetGeleverdeProdPerLeverancier
     * Haalt de verschillende geleverde producten per leverancier op.
     */
    public static function getGeleverdeProducten(int $leverancierId): array
    {
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

    /**
     * Stored Procedure equivalent: sp_SlaLeveringOp
     * Voegt een nieuwe levering toe en hoogt de magazijnvoorraad op.
     */
    public static function slaLeveringOp(int $leverancierId, int $productId, int $aantal, string $datumEerstVolgende): void
    {
        $datumLevering = now()->toDateString();

        DB::insert(
            'INSERT INTO ProductPerLeverancier (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering, IsActief, DatumAangemaakt, DatumGewijzigd)
             VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
            [$leverancierId, $productId, $datumLevering, $aantal, $datumEerstVolgende]
        );

        DB::update(
            'UPDATE Magazijn SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + ?, DatumGewijzigd = CURRENT_TIMESTAMP WHERE ProductId = ?',
            [$aantal, $productId]
        );
    }
}
