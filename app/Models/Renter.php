<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renter extends Model
{
    use HasFactory;

    protected $table = 'renter';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fullName',
        'phone',
        'nidNumber',
        'address',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'renterId');
    }
}
