<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact';
    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'contactOwnerId',
        'contactSourceId',
        'contactStageId',
        'firstName',
        'lastName',
        'dateOfBirth',
        'companyId',
        'jobTitle',
        'department',
        'industryId',
        'email',
        'phone',
        'twitter',
        'linkedin',
        'presentAddress',
        'presentCity',
        'presentZipCode',
        'presentState',
        'presentCountry',
        'permanentAddress',
        'permanentCity',
        'permanentZipCode',
        'permanentState',
        'permanentCountry',
        'description',
    ];

    public function contactOwner(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'contactOwnerId');
    }

    public function contactSource(): BelongsTo
    {
        return $this->belongsTo(ContactSource::class, 'contactSourceId');
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

    public function project(): HasMany
    {
        return $this->hasMany(Project::class, 'contactId');
    }

}
