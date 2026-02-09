<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $table = 'flat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'floorId',
        'flatNo',
        'roomQty',
        'washroomQty',
        'hasVeranda',
        'hasKitchen',
        'rent',
        'status',
    ];

    protected $casts = [
        'hasVeranda' => 'boolean',
        'hasKitchen' => 'boolean',
    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floorId');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'flatId');
    }
}
