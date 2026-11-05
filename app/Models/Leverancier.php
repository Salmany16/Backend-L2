<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use PDO;

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
     * Uitgevoerd via PDO (Verplicht voor Opdracht 2)
     */
    public static function getOverzicht(): array
    {
        $pdo = DB::connection()->getPdo();

        if (DB::connection()->getDriverName() === 'mysql') {
            $stmt = $pdo->prepare('CALL sp_GetLeveranciersOverzicht()');
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();

            return $results;
        }

        // SQLite fallback via PDO prepare
        $stmt = $pdo->prepare('
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
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Stored Procedure: sp_GetGeleverdeProdPerLeverancier
     * Uitgevoerd via PDO (Verplicht voor Opdracht 2)
     */
    public static function getGeleverdeProducten(int $leverancierId): array
    {
        $pdo = DB::connection()->getPdo();

        if (DB::connection()->getDriverName() === 'mysql') {
            $stmt = $pdo->prepare('CALL sp_GetGeleverdeProdPerLeverancier(:leverancierId)');
            $stmt->bindValue(':leverancierId', $leverancierId, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();

            return $results;
        }

        // SQLite fallback via PDO prepare
        $stmt = $pdo->prepare('
            SELECT p.Id as ProductId,
                   p.Naam as ProductNaam,
                   p.IsActief,
                   m.VerpakkingsEenheid,
                   m.AantalAanwezig,
                   MAX(ppl.DatumLevering) as LaatsteLevering
            FROM ProductPerLeverancier ppl
            JOIN Product p ON ppl.ProductId = p.Id
            LEFT JOIN Magazijn m ON p.Id = m.ProductId
            WHERE ppl.LeverancierId = :leverancierId
            GROUP BY p.Id, p.Naam, p.IsActief, m.VerpakkingsEenheid, m.AantalAanwezig
            ORDER BY m.AantalAanwezig DESC
        ');
        $stmt->bindValue(':leverancierId', $leverancierId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Stored Procedure: sp_SlaLeveringOp
     * Uitgevoerd via PDO (Verplicht voor Opdracht 2)
     */
    public static function slaLeveringOp(int $leverancierId, int $productId, int $aantal, string $datumEerstVolgende): void
    {
        $pdo = DB::connection()->getPdo();

        if (DB::connection()->getDriverName() === 'mysql') {
            $stmt = $pdo->prepare('CALL sp_SlaLeveringOp(:leverancierId, :productId, :aantal, :datumEerstVolgende)');
            $stmt->bindValue(':leverancierId', $leverancierId, PDO::PARAM_INT);
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $stmt->bindValue(':aantal', $aantal, PDO::PARAM_INT);
            $stmt->bindValue(':datumEerstVolgende', $datumEerstVolgende, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();

            return;
        }

        // SQLite fallback via PDO prepare
        $datumLevering = now()->toDateString();
        $stmt1 = $pdo->prepare('
            INSERT INTO ProductPerLeverancier (LeverancierId, ProductId, DatumLevering, Aantal, DatumEerstVolgendeLevering, IsActief, DatumAangemaakt, DatumGewijzigd)
            VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ');
        $stmt1->execute([$leverancierId, $productId, $datumLevering, $aantal, $datumEerstVolgende]);

        $stmt2 = $pdo->prepare('
            UPDATE Magazijn SET AantalAanwezig = COALESCE(AantalAanwezig, 0) + ?, DatumGewijzigd = CURRENT_TIMESTAMP WHERE ProductId = ?
        ');
        $stmt2->execute([$aantal, $productId]);
    }
}
