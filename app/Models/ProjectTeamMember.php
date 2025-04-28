<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTeamMember extends Model
{
    use HasFactory;
    protected $table = 'projectTeamMember';
    protected $primaryKey = 'id';
    protected $fillable = [
        'projectTeamId',
        'userId',
    ];

    public function projectTeam(): BelongsTo
    {
        return $this->belongsTo(ProjectTeam::class, 'projectTeamId');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'userId');
    }
}
