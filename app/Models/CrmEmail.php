<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmEmail extends Model
{
    use HasFactory;

    protected $table = 'crmEmail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'emailOwnerId',
        'contactId',
        'companyId',
        'opportunityId',
        'quoteId',
        'senderEmail',
        'receiverEmail',
        'subject',
        'body',
        'emailStatus',
    ];

    public function emailOwner(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'emailOwnerId', 'id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId', 'id');
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class, 'opportunityId', 'id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quoteId', 'id');
    }

    public function bcc(): HasMany
    {
        return $this->hasMany(Bcc::class, 'crmEmailId', 'id');
    }

    public function cc(): HasMany
    {
        return $this->hasMany(Cc::class, 'crmEmailId', 'id');
    }

}
