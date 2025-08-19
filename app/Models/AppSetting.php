<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;
    protected $table = 'appSetting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'companyName',
        'dashboardType',
        'tagLine',
        'address',
        'phone',
        'email',
        'website',
        'footer',
        'logo',
        'currencyId',
        'bin',
        'mushak',
        'isSaleCommission',
        'isPos'
    ];
}
