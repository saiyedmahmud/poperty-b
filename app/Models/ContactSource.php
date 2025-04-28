<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactSource extends Model
{
    use HasFactory;

    protected $table = 'contactSource';
    protected $primaryKey = 'id';

    protected $fillable = [
        'contactSourceName',
    ];

    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class, 'contactSourceId');
    }
}
