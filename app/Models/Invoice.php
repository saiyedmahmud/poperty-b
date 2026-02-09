<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rentalId',
        'otherBill',
        'rentAmount',
        'totalAmount',
        'dueAmount',
        'invoiceMonth',
        'status',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rentalId');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoiceId');
    }
}
