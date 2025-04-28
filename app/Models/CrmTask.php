<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmTask extends Model
{
    use HasFactory;

    protected $table = 'crmTask';
    protected $primaryKey = 'id';
    protected $fillable = [
        'taskName',
        'taskTypeId',
        'priorityId',
        'taskStatusId',
        'assigneeId',
        'contactId',
        'companyId',
        'opportunityId',
        'quoteId',
        'dueDate',
        'notes',
    ];

    public function taskType():BelongsTo
    {
        return $this->belongsTo(CrmTaskType::class, 'taskTypeId');
    }

    public function priority():BelongsTo
    {
        return $this->belongsTo(Priority::class, 'priorityId');
    }

    public function taskStatus():BelongsTo
    {
        return $this->belongsTo(CrmTaskStatus::class, 'taskStatusId');
    }

    public function assignee():BelongsTo
    {
        return $this->belongsTo(Users::class, 'assigneeId');
    }

    public function contact():BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function opportunity():BelongsTo
    {
        return $this->belongsTo(Opportunity::class, 'opportunityId');
    }

    public function quote():BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quoteId');
    }
}