<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteStage extends Model
{
    use HasFactory;
    protected $table = 'quoteStage';
    protected $fillable = [
        'quoteStageName',
    ];

    public function quote(): HasMany
    {
        return $this->hasMany(Quote::class, 'quoteStageId');
    }
}
