<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Allergeen extends Model
{
    use HasFactory;

    protected $table = 'Allergeen';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    /**
     * Stored Procedure: sp_GetAllergenenPerProduct
     */
    public static function getAllergenenVoorProduct(int $productId): array
    {
        return DB::select('CALL sp_GetAllergenenPerProduct(?)', [$productId]);
    }
}
