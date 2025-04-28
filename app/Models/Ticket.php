<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'ticket';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ticketId',
        'customerId',
        'email',
        'subject',
        'description',
        'ticketResolveTime',
        'ticketCategoryId',
        'priorityId',
        'ticketStatusId',
    ];

    public function customer():belongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

    public function ticketCategory():belongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticketCategoryId');
    }

    public function priority():belongsTo
    {
        return $this->belongsTo(Priority::class, 'priorityId');
    }

    public function ticketStatus():belongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'ticketStatusId');
    }

    public function images():HasMany
    {
        return $this->hasMany(Images::class, 'ticketId', 'ticketId');
    }

    public function ticketComment():HasMany
    {
        return $this->hasMany(TicketComment::class, 'ticketId', 'ticketId');
    }
    


}
