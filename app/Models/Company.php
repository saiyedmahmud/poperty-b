<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    //create company model
    protected $table = 'company';
    protected $primaryKey = 'id';
    protected $fillable = [
        'companyOwnerId',
        'companyName',
        'image',
        'industryId',
        'companyTypeId',
        'companySize',
        'annualRevenue',
        'website',
        'phone',
        'email',
        'linkedin',
        'facebook',
        'twitter',
        'instagram',
        'billingStreet',
        'billingCity',
        'billingState',
        'billingZipCode',
        'billingCountry',
        'shippingStreet',
        'shippingCity',
        'shippingState',
        'shippingZipCode',
        'shippingCountry',
    ];

    //create relationship with user
    public function companyOwner(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'companyOwnerId');
    }

    //create relationship with industry
    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industryId');
    }

    //create relationship with companyType
    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class, 'companyTypeId');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class, 'companyId');
    }

    public function opportunity(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'companyId');
    }

    public function note(): HasMany
    {
        return $this->hasMany(Note::class, 'companyId');
    }

    public function quote(): HasMany
    {
        return $this->hasMany(Quote::class, 'companyId');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'companyId');
    }


    public function attachment(): HasMany
    {
        return $this->hasMany(Attachment::class, 'companyId');
    }

    public function crmEmail(): HasMany
    {
        return $this->hasMany(CrmEmail::class, 'companyId');
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class, 'companyId');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'companyId');
    }

}
