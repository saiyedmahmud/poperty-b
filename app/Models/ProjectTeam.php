<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTeam extends Model
{
    use HasFactory;
    protected $table = 'projectTeam';
    protected $primaryKey = 'id';
    protected $fillable = [
        'projectTeamName',
        'projectId'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function projectTeamMember(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class, 'projectTeamId');
    }


    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'projectTeamId');
    }
}
