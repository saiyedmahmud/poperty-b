<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpportunitySource extends Model
{
    use HasFactory;

    protected $table = 'opportunitySource';
    protected $primaryKey = 'id';

    protected $fillable = [
        'opportunitySourceName',
    ];

    public function opportunity():HasMany
    {
        return $this->hasMany(Opportunity::class, 'opportunitySourceId');
    }
}
