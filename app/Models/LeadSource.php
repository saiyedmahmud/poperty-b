<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    use HasFactory;
    protected $table = 'leadSource';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        
    ];

    public function lead()
    {
        return $this->hasMany(Lead::class, 'leadSourceId');
    }

}
