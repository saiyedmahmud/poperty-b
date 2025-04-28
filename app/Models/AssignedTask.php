<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignedTask extends Model
{
    use HasFactory;
    protected $table = 'assignedTask';
    protected $primaryKey = 'id';
    protected $fillable = [
        'taskId',
        'userId',
    ];

    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, 'taskId');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
