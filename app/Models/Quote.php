<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'quote';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quoteOwnerId',
        'companyId',
        'contactId',
        'opportunityId',
        'quoteStageId',
        'quoteName',
        'quoteDate',
        'expirationDate',
        'quoteStageId',
        'termsAndConditions',
        'description',
        'discount',
        'totalAmount',
    ];

    public function quoteOwner(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'quoteOwnerId');
    }

     public function quoteStage():BelongsTo
     {
         return $this->belongsTo(QuoteStage::class, 'quoteStageId');
     }

    public function quoteProduct(): HasMany
    {
        return $this->hasMany(QuoteProduct::class, 'quoteId');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class, 'opportunityId');
    }

    public function email(): HasMany
    {
        return $this->hasMany(Email::class, 'quoteId');
    }

}
