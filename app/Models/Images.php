<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Images extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ticketId',
        'ticketCommentId',
        'imageName',
    ];

    public function ticket():BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticketId');
    }

    public function ticketComment():BelongsTo
    {
        return $this->belongsTo(TicketComment::class, 'ticketCommentId');
    }

}
