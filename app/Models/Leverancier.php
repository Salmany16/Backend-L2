<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Leverancier extends Model
{
    use HasFactory;

    protected $table = 'Leverancier';

    protected $primaryKey = 'Id';

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public static function getOverzicht(): array
    {
        return DB::select('CALL sp_GetLeveranciersOverzicht()');
    }

    public static function getGeleverdeProducten(int $leverancierId): array
    {
        return DB::select('CALL sp_GetGeleverdeProdPerLeverancier(?)', [$leverancierId]);
    }

    public static function getById(int $id): ?object
    {
        return DB::select('CALL sp_GetLeverancierById(?)', [$id])[0] ?? null;
    }

    public static function slaLeveringOp(int $leverancierId, int $productId, int $aantal, string $datumEerstVolgende): void
    {
        DB::statement('CALL sp_SlaLeveringOp(?, ?, ?, ?)', [$leverancierId, $productId, $aantal, $datumEerstVolgende]);
    }
}
