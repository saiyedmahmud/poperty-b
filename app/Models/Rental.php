<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $table = 'rental';
    protected $primaryKey = 'id';
    protected $fillable = [
        'flatId',
        'renterId',
        'startDate',
        'endDate',
        'securityDeposit',
        'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
        'startDate' => 'date',
        'endDate' => 'date',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flatId');
    }

    public function renter()
    {
        return $this->belongsTo(Renter::class, 'renterId');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'rentalId');
    }
}
