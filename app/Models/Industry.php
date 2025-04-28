<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;
    protected $table = 'industry';
    protected $primaryKey = 'id';
    protected $fillable = [
        'industryName',
    ];

    //create relationship with company
    public function company(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Company::class, 'industryId');
    }
}
