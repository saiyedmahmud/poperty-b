<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opportunity extends Model
{
    use HasFactory;

    protected $table = 'opportunity';
    protected $primaryKey = 'id';

    protected $fillable = [
        'opportunityOwnerId',
        'contactId',
        'companyId',
        'opportunityName',
        'amount',
        'opportunityTypeId',
        'opportunityStageId',
        'opportunitySourceId',
        'opportunityCreateDate',
        'opportunityCloseDate',
        'nextStep',
        'competitors',
        'description',
    ];

    public function opportunityType(): BelongsTo
    {
        return $this->belongsTo(OpportunityType::class, 'opportunityTypeId');
    }

    public function opportunityStage(): BelongsTo
    {
        return $this->belongsTo(OpportunityStage::class, 'opportunityStageId');
    }

    public function opportunitySource(): BelongsTo
    {
        return $this->belongsTo(OpportunitySource::class, 'opportunitySourceId');
    }

    public function opportunityOwner(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'opportunityOwnerId');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function note(): HasMany
    {
        return $this->hasMany(Note::class, 'opportunityId');
    }

    public function quote(): HasMany
    {
        return $this->hasMany(Quote::class, 'opportunityId');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'opportunityId');
    }

    public function attachment(): HasMany
    {
        return $this->hasMany(Attachment::class, 'opportunityId');
    }

    public function crmEmail(): HasMany
    {
        return $this->hasMany(CrmEmail::class, 'opportunityId');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'opportunityId');
    }

}
