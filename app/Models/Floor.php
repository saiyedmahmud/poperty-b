<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $table = 'floor';
    protected $primaryKey = 'id';
    protected $fillable = [
        'buildingId',
        'floorNumber',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'buildingId');
    }

    public function flats()
    {
        return $this->hasMany(Flat::class, 'floorId');
    }
}
