<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactStage extends Model
{
    use HasFactory;

    protected $table = 'contactStage';
    protected $primaryKey = 'id';

    protected $fillable = [
        'contactStageName',
    ];

    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class, 'contactStageId');
    }
}
