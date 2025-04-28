<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmTaskStatus extends Model
{
    use HasFactory;

    protected $table = 'crmTaskStatus';
    protected $primaryKey = 'id';

    protected $fillable = [
        'taskStatusName',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'crmTaskStatusId', 'id');
    }

}
