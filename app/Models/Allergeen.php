<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use PDO;

class Allergeen extends Model
{
    use HasFactory;

    protected $table = 'Allergeen';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'Naam',
        'Omschrijving',
        'IsActief',
        'Opmerking',
    ];

    protected $casts = [
        'IsActief' => 'boolean',
    ];

    public function productPerAllergenen(): HasMany
    {
        return $this->hasMany(ProductPerAllergeen::class, 'AllergeenId', 'Id');
    }

    /**
     * Stored Procedure: sp_GetAllergenenPerProduct via PDO
     */
    public static function getAllergenenVoorProduct(int $productId): array
    {
        $pdo = DB::connection()->getPdo();

        if (DB::connection()->getDriverName() === 'mysql') {
            $stmt = $pdo->prepare('CALL sp_GetAllergenenPerProduct(:productId)');
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            $stmt->closeCursor();

            return $results;
        }

        // SQLite fallback via PDO prepare
        $stmt = $pdo->prepare('
            SELECT a.Naam, a.Omschrijving
            FROM ProductPerAllergeen ppa
            JOIN Allergeen a ON ppa.AllergeenId = a.Id
            WHERE ppa.ProductId = :productId
            ORDER BY a.Naam ASC
        ');
        $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
