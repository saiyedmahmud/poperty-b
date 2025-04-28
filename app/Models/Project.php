<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    protected $table = 'project';
    protected $primaryKey = 'id';
    protected $fillable = [
        'projectManagerId',
        'contactId',
        'priorityId',
        'projectValue',
        'projectStatus',
        'name',
        'startDate',
        'endDate',
        'description'
    ];

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'projectManagerId');
    }

    public function milestone(): HasMany
    {
        return $this->hasMany(Milestone::class, 'projectId');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'projectId');
    }

    //project team
    public function projectTeam(): HasMany
    {
        return $this->hasMany(ProjectTeam::class, 'projectId');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class, 'priorityId');
    }


}
