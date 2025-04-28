<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    use HasFactory;

    protected $table = 'ticketCategory';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ticketCategoryName',
    ];

    public function ticket():HasMany
    {
        return $this->hasMany(Ticket::class, 'ticketCategoryId');
    }
}
