<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
     * Stored Procedure: sp_GetAllergenenPerProduct
     */
    public static function getAllergenenVoorProduct(int $productId): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return DB::select('
                SELECT a.Naam, a.Omschrijving
                FROM ProductPerAllergeen ppa
                JOIN Allergeen a ON ppa.AllergeenId = a.Id
                WHERE ppa.ProductId = ?
                ORDER BY a.Naam ASC
            ', [$productId]);
        }

        return DB::select('CALL sp_GetAllergenenPerProduct(?)', [$productId]);
    }
}
