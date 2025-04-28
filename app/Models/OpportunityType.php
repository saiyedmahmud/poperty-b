<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpportunityType extends Model
{
    use HasFactory;

    protected $table = 'opportunityType';
    protected $primaryKey = 'id';

    protected $fillable = [
        'opportunityTypeName',
    ];

    public function opportunity():HasMany
    {
        return $this->hasMany(Opportunity::class, 'opportunityTypeId');
    }
}
