<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketComment extends Model
{
    use HasFactory;

    protected $table = 'ticketComment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ticketId',
        'repliedBy',
        'userType',
        'description'
    ];

    public function ticket():BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticketId', 'ticketId');
    }

    public function images():HasMany
    {
        return $this->hasMany(Images::class, 'ticketCommentId');
    }
}
