<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyType extends Model
{
    use HasFactory;
    protected $table = 'companyType';
    protected $primaryKey = 'id';
    protected $fillable = [
        'companyTypeName',
    ];

    //create relationship with company
    public function company(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Company::class, 'companyTypeId');
    }
}
