<?php

namespace App\Models;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasks extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'name',
        'priorityId',
        'taskTypeId',
        'crmTaskStatusId',
        'milestoneId',
        'taskStatusId',
        'projectId',
        'assigneeId',
        'contactId',
        'companyId',
        'opportunityId',
        'quoteId',
        'teamId',
        'startDate',
        'endDate',
        'description',
    ];

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priorityId');
    }

    public function taskType()
    {
        return $this->belongsTo(CrmTaskType::class, 'taskTypeId');
    }

    public function taskStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'taskStatusId');
    }

    public function crmTaskStatus()
    {
        return $this->belongsTo(CrmTaskStatus::class, 'crmTaskStatusId');
    }

    public function assignee()
    {
        return $this->belongsTo(Users::class, 'assigneeId');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companyId');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'opportunityId');
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quoteId');
    }

    public function assignedTask()
    {
        return $this->hasMany(AssignedTask::class, 'taskId');
    }

    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function milestone():BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'taskId');
    }

    public function team():BelongsTo
    {
        return $this->belongsTo(ProjectTeam::class, 'teamId');
    }

}
