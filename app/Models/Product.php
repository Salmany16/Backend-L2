<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Product';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $fillable = [
        'Naam',
        'Barcode',
        'IsActief',
        'Opmerking',
    ];

    protected $casts = [
        'IsActief' => 'boolean',
    ];

    public function magazijn(): HasOne
    {
        return $this->hasOne(Magazijn::class, 'ProductId', 'Id');
    }

    public function leveringen(): HasMany
    {
        return $this->hasMany(ProductPerLeverancier::class, 'ProductId', 'Id');
    }

    public function productPerAllergenen(): HasMany
    {
        return $this->hasMany(ProductPerAllergeen::class, 'ProductId', 'Id');
    }

    /**
     * Stored Procedure: sp_GetMagazijnOverzicht
     */
    public static function getMagazijnOverzicht(): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return DB::select('
                SELECT p.Id,
                       p.Naam,
                       p.Barcode,
                       m.VerpakkingsEenheid,
                       m.AantalAanwezig
                FROM Product p
                LEFT JOIN Magazijn m ON p.Id = m.ProductId
                WHERE p.IsActief = 1
                ORDER BY p.Barcode ASC
            ');
        }

        return DB::select('CALL sp_GetMagazijnOverzicht()');
    }
}
