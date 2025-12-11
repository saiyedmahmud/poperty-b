<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoModule extends Model
{
    use HasFactory;

    protected $table = 'demoModule';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
