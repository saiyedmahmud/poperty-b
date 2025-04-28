<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    use HasFactory;
    protected $table = 'milestone';
    protected $primaryKey = 'id';
    protected $fillable = [
        'projectId',
        'name',
        'startDate',
        'endDate',
        'description'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, 'milestoneId');
    }
}
