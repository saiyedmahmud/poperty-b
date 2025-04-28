<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskStatus extends Model
{
    use HasFactory;
    protected $table = 'taskStatus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'projectId',
        'name',
        'status'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'taskStatusId');
    }
}
