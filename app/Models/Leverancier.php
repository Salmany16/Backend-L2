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

    /**
     * Stored Procedure: sp_GetLeveranciersOverzicht
     */
    public static function getOverzicht(): array
    {
        return DB::select('CALL sp_GetLeveranciersOverzicht()');
    }

    /**
     * Stored Procedure: sp_GetGeleverdeProdPerLeverancier
     */
    public static function getGeleverdeProducten(int $leverancierId): array
    {
        return DB::select('CALL sp_GetGeleverdeProdPerLeverancier(?)', [$leverancierId]);
    }

    /**
     * Stored Procedure: sp_GetLeverancierById
     */
    public static function getById(int $id): ?object
    {
        return DB::select('CALL sp_GetLeverancierById(?)', [$id])[0] ?? null;
    }

    /**
     * Stored Procedure: sp_SlaLeveringOp
     */
    public static function slaLeveringOp(int $leverancierId, int $productId, int $aantal, string $datumEerstVolgende): void
    {
        DB::statement('CALL sp_SlaLeveringOp(?, ?, ?, ?)', [$leverancierId, $productId, $aantal, $datumEerstVolgende]);
    }

    /**
     * BE-opdracht 03 - Stored Procedure: sp_GetLeveranciersOverzichtPaginering
     */
    public static function getOverzichtPaginering(int $offset, int $limit): array
    {
        return DB::select('CALL sp_GetLeveranciersOverzichtPaginering(?, ?)', [$offset, $limit]);
    }

    /**
     * BE-opdracht 03 - Stored Procedure: sp_GetLeveranciersCount
     */
    public static function getCount(): int
    {
        return (int) (DB::select('CALL sp_GetLeveranciersCount()')[0]->Totaal ?? 0);
    }

    /**
     * BE-opdracht 03 - Stored Procedure: sp_GetLeverancierDetailsEnAdres
     */
    public static function getDetailsEnAdres(int $leverancierId): ?object
    {
        return DB::select('CALL sp_GetLeverancierDetailsEnAdres(?)', [$leverancierId])[0] ?? null;
    }

    /**
     * BE-opdracht 03 - Stored Procedure: sp_UpdateLeverancierEnContact
     */
    public static function updateEnContact(
        int $leverancierId,
        string $naam,
        string $contactPersoon,
        string $leverancierNummer,
        string $mobiel,
        string $straatnaam,
        int $huisnummer,
        string $postcode,
        string $stad
    ): void {
        DB::statement('CALL sp_UpdateLeverancierEnContact(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $leverancierId,
            $naam,
            $contactPersoon,
            $leverancierNummer,
            $mobiel,
            $straatnaam,
            $huisnummer,
            $postcode,
            $stad,
        ]);
    }
}
