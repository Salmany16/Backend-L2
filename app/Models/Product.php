<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}
