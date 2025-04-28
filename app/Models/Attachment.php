<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'attachment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'attachmentOwnerId',
        'companyId',
        'contactId',
        'opportunityId',
        'quoteId',
        'attachmentPath',
        'attachmentName',
    ];

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

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quoteId');
    }

    public function attachmentOwner(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'attachmentOwnerId');
    }

    public function email(): HasMany
    {
        return $this->hasMany(Email::class, 'emailId');
    }

}

