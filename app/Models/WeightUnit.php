<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeightUnit extends Model
{
    use HasFactory;

    protected $table = 'weightUnit';

    protected $fillable = [
        'name'
    ];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'weightUnitId');
    }
}
