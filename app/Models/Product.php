<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Product';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function magazijn()
    {
        return $this->hasOne(Magazijn::class, 'ProductId', 'Id');
    }

    public static function getMagazijnOverzicht(): array
    {
        return DB::select('CALL sp_GetMagazijnOverzicht()');
    }

    public static function getById(int $id): ?object
    {
        return DB::select('CALL sp_GetProductById(?)', [$id])[0] ?? null;
    }
}
