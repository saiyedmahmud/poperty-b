<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpportunityStage extends Model
{
    use HasFactory;

    protected $table = 'opportunityStage';
    protected $primaryKey = 'id';

    protected $fillable = [
        'opportunityStageName',
    ];

    public function opportunity():HasMany
    {
        return $this->hasMany(Opportunity::class, 'opportunityStageId');
    }
}
