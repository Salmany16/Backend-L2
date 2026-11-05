<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Levering extends Model
{
    use HasFactory;

    protected $table = 'ProductPerLeverancier';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public static function getLeveringDetails(int $productId): array
    {
        return DB::select('CALL sp_GetLeveringDetails(?)', [$productId]);
    }

    public static function getProductLeveringDetails(int $leverancierId, int $productId): ?object
    {
        return DB::select('CALL sp_GetProductLeveringDetails(?, ?)', [$leverancierId, $productId])[0] ?? null;
    }

    public static function voegLeveringToe(int $leverancierId, int $productId, int $aantal, ?string $datumEerstvolgende = null): void
    {
        DB::statement('CALL sp_SlaLeveringOp(?, ?, ?, ?)', [$leverancierId, $productId, $aantal, $datumEerstvolgende ?? '']);
    }
}
