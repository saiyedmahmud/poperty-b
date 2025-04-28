<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'phone',
        'address',
        'password',
        'roleId',
        'username',
        'googleId',
        'firstName',
        'lastName',
        'profileImage',
        'contactId',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roleId');
    }

    public function saleInvoice(): HasMany
    {
        return $this->hasMany(SaleInvoice::class, 'customerId');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function ticket(): HasMany
    {
        return $this->hasMany(Ticket::class, 'customerId');
    }

    public function ticketStatus(): HasMany
    {
        return $this->hasMany(TicketStatus::class, 'customerId');
    }

    public function ticketCategory(): HasMany
    {
        return $this->hasMany(TicketCategory::class, 'customerId');
    }

    public function priority(): HasMany
    {
        return $this->hasMany(Priority::class, 'customerId');
    }
}
