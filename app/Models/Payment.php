<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'invoiceId',
        'amount',
        'paymentDate',
        'paymentMethod',
    ];

    protected $casts = [
        'paymentDate' => 'date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoiceId');
    }
}
