<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmTaskType extends Model
{
    use HasFactory;

    protected $table = 'crmTaskType';
    protected $primaryKey = 'id';

    protected $fillable = [
        'taskTypeName',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'TaskTypeId', 'id');
    }
}
