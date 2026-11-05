<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
