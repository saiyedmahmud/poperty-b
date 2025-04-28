<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketStatus extends Model
{
    use HasFactory;

    protected $table = 'ticketStatus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ticketStatusName',
    ];

    public function ticket():HasMany
    {
        return $this->hasMany(Ticket::class, 'ticketStatusId');
    }

  
}
