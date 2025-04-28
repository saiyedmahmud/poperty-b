<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickLinks extends Model
{
    use HasFactory;

    protected $table = 'quickLinks';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'position', 'icon', 'url'];

}
