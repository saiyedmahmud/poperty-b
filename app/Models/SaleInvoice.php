<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoice extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'saleInvoice';
    protected $primaryKey = 'id';
    protected string $key = 'string';

    protected $fillable = [
        'date',
        'invoiceMemoNo',
        'totalAmount',
        'totalTaxAmount',
        'totalDiscountAmount',
        'paidAmount',
        'dueAmount',
        'contactId',
        'companyId',
        'termsAndConditions',
        'note',
        'address',
        'dueDate',
        'userId',
        'paymentStatus',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUniqueKey(13);
        });
    }

    /**
     * @throws Exception
     */
    protected static function generateUniqueKey($length): string
    {
        $characters = "ABCDEFGHOPQRSTUYZ0123456IJKLMN789VWX";
        $key = "S_";

        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }
        // Ensure the key is unique
        while (static::where('id', $key)->exists()) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $key;
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'userId');
    }

    public function saleInvoiceProduct(): HasMany
    {
        return $this->hasMany(SaleInvoiceProduct::class, 'invoiceId');
    }

}
