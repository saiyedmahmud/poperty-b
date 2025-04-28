<?php

namespace App\Models;

use App\Models\LeadSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'lead';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'leadOwnerId',
        'leadSourceId',
        'leadStatus',
        'leadValue',
        'contactStatus',
         'status'
    ];

    public function leadOwner()
    {
        return $this->belongsTo(Users::class, 'leadOwnerId', 'id');
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class, 'leadSourceId', 'id');
    }



    public function contactStage(): BelongsTo
    {
        return $this->belongsTo(ContactStage::class, 'contactStageId');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industryId');
    }

    public function opportunity(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'contactId');
    }

    public function note(): HasMany
    {
        return $this->hasMany(Note::class, 'contactId');
    }

    public function quote(): HasMany
    {
        return $this->hasMany(Quote::class, 'contactId');
    }

    public function Tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'contactId');
    }


    public function attachment(): HasMany
    {
        return $this->hasMany(Attachment::class, 'contactId');
    }

    public function crmEmail(): HasMany
    {
        return $this->hasMany(CrmEmail::class, 'contactId');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class, 'contactId');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'contactId');
    }
    
}
