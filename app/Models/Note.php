<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $table = 'note';
    protected $primaryKey = 'id';
    protected $fillable = [
        'noteOwnerId',
        'contactId',
        'companyId',
        'opportunityId',
        'quoteId',
        'title',
        'description',
        'status'
    ];

    public function noteOwner():BelongsTo
    {
        return $this->belongsTo(Users::class, 'noteOwnerId');
    }

    public function contact():BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function opportunity():BelongsTo
    {
        return $this->belongsTo(Opportunity::class, 'opportunityId');
    }

    public function quote():BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quoteId');
    }
}
