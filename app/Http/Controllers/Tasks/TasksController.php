<?php

namespace App\Http\Controllers\Tasks;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Tasks;
use App\Models\AssignedTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{

    public function createTasks(Request $request)
    {
        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    return response()->json(['error' => $error], 400);
                }
            }
            $createTask = Tasks::create([
                'projectId' => $request->projectId,
                'milestoneId' => $request->milestoneId ?? null,
                'priorityId' => $request->priorityId ?? null,
                'taskStatusId' => $request->taskStatusId ?? null,
                'name' => $request->name,
                'teamId' => $request->teamId ?? null,
                'startDate' => Carbon::parse($request->startDate)->format('Y-m-d') ?? null,
                'endDate' => Carbon::parse($request->endDate)->format('Y-m-d') ?? null,
                'description' => $request->description ?? null,
            ]);

            if (!$createTask) {
                DB::rollBack();
                return response()->json(['error' => 'An error occurred during creating Tasks. Please try again later.'], 500);
            }

            if ($request->assignedTask) {
                foreach ($request->assignedTask as $item) {
                    AssignedTask::create([
                        'taskId' => $createTask->id,
                        'userId' => $item,
                    ]);
                }
            }

            $converted = arrayKeysToCamelCase($createTask->toArray());
            DB::commit();
            return response()->json($converted, 200);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred during creating Tasks. Please try again later.',
                'message' => $err->getMessage()
            ], 500);
        }
    }

    public function getAllTasks(Request $request)
    {
        try {
            if ($request->query('type') === 'project') {
                if ($request->query('query') === 'all') {
                    try {
                        $task = Tasks::orderBy('id', 'desc')
                            ->where('status', 'true')
                            ->where('type', 'project')
                            ->get();

                        $converted = arrayKeysToCamelCase($task->toArray());
                        return response()->json($converted, 200);
                    } catch (Exception $err) {
                        return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
                    }
                } else if ($request->query('status')) {
                    try {
                        $pagination = getPagination($request->query());
                        $task = Tasks::where('status', $request->query('status'))
                            ->where('type', 'project')
                            ->with('taskStatus', 'priority', 'milestone', 'project',)
                            ->orderBy('id', 'desc')
                            ->skip($pagination['skip'])
                            ->take($pagination['limit'])
                            ->get();

                        $taskCount = Tasks::where('status', $request->query('status'))
                            ->count();

                        $converted = arrayKeysToCamelCase($task->toArray());
                        $finalResult = [
                            'getAllTask' => $converted,
                            'totalTask' => $taskCount,
                        ];

                        return response()->json($finalResult);
                    } catch (Exception $err) {
                        return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
                    }
                } else {
                    return response()->json(['error' => 'Invalid query'], 400);
                }
            } else if ($request->query('type') === 'crm') {

                if ($request->query('query') === 'all') {
                    try {
                        $allTask = Tasks::where('type', 'crm')
                            ->with('taskType:id,taskTypeName', 'priority', 'taskStatus:id,taskStatusName', 'assignee:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName')
                            ->orderBy('id', 'desc')
                            ->get();

                        $allTask->each(function ($task) {
                            $task->assignee->fullName = $task->assignee->firstName . " " . $task->assignee->lastName;

                            $task->contact->fullName = $task->contact->firstName . " " . $task->contact->lastName;
                        });

                        $converted = arrayKeysToCamelCase($allTask->toArray());
                        return response()->json($converted, 200);
                    } catch (Exception $err) {
                        return response()->json(['error' => 'An error occurred during getting all crm task. Please try again later.'], 500);
                    }
                } else if ($request->query('query') === 'search') {
                    $pagination = getPagination($request->query());

                    $allTask = Tasks::where('taskName', 'LIKE', '%' . $request->query('key') . '%')
                        ->where('type', 'crm')
                        ->with('taskType:id,taskTypeName', 'priority', 'taskStatus:id,taskStatusName', 'assignee:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName')
                        ->orderBy('id', 'desc')
                        ->skip($pagination['skip'])
                        ->take($pagination['limit'])
                        ->get();

                    $total = Tasks::all()->count();

                    $allTask->each(function ($task) {
                        $task->assignee->fullName = $task->assignee->firstName . " " . $task->assignee->lastName;
                        $task->contact->fullName = $task->contact->firstName . " " . $task->contact->lastName;
                    });

                    $converted = arrayKeysToCamelCase($allTask->toArray());

                    $finalResult = [
                        'getAllTask' => $converted,
                        "totalTask" => $total,
                    ];

                    return response()->json($finalResult, 200);
                } else if ($request->query()) {
                    try {
                        $pagination = getPagination($request->query());

                        $allTask = Tasks::when($request->query('status'), function ($query) use ($request) {
                            $query->whereIn('status', explode(',', $request->query('status')));
                        })
                            ->with('taskType:id,taskTypeName', 'priority', 'taskStatus:id,Name', 'assignee:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName')
                            ->orderBy('id', 'desc')
                            ->where('type', 'crm')
                            ->skip($pagination['skip'])
                            ->take($pagination['limit'])
                            ->get();

                        $allTask->each(function ($task) {
                            if ($task->assignee) {
                                $task->assignee->fullName = $task->assignee->firstName . " " . $task->assignee->lastName;
                            }
                            if ($task->contact) {
                                $task->contact->fullName = $task->contact->firstName . " " . $task->contact->lastName;
                            }
                        });

                        $converted = arrayKeysToCamelCase($allTask->toArray());

                        $finalResult = [
                            'getAllTask' => $converted,
                            "totalTask" => count($allTask),
                        ];
                        return response()->json($finalResult, 200);
                    } catch (Exception $err) {

                        return response()->json(['error' => 'An error occurred during getting all crm task. Please try again later.', "msg" => $err->getMessage()], 500);
                    }
                }
            } else {
                if ($request->query('query') === 'all') {
                    try {
                        $task = Tasks::orderBy('id', 'desc')
                            ->where('status', 'true')
                            ->get();

                        $converted = arrayKeysToCamelCase($task->toArray());
                        return response()->json($converted, 200);
                    } catch (Exception $err) {
                        return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
                    }
                } else if ($request->query('query') === 'myTask' && $request->query('status')) {
                    try {
                        $pagination = getPagination($request->query());
                        $data = $request->attributes->get('data');
                        $id = $data['sub'];

                        $crmTask = Tasks::where('status', $request->query('status'))
                            ->where('assigneeId', $id)
                            ->with([
                                'taskStatus',
                                'priority',
                                'milestone',
                                'project',

                            ])
                            ->orderBy('id', 'desc')
                            ->skip($pagination['skip'])
                            ->take($pagination['limit'])
                            ->get();

                        $projectTask = Tasks::where('status', $request->query('status'))
                            ->where('type', 'project')
                            ->whereHas('assignedTask.user', function ($query) use ($id) {
                                $query->where('id', $id);
                            })
                            ->with([
                                'taskStatus',
                                'priority',
                                'milestone',
                                'project',
                                'assignedTask.user',
                                'assignedTask.user:id,firstName,lastName,username',
                            ])
                            ->orderBy('id', 'desc')
                            ->skip($pagination['skip'])
                            ->take($pagination['limit'])
                            ->get();


                        //marge the two collection
                        $task = $crmTask->merge($projectTask);

                        //count the total task
                        $taskCount = $task->count();

                        $converted = arrayKeysToCamelCase($task->toArray());
                        $finalResult = [
                            'getAllTask' => $converted,
                            'totalTask' => $taskCount,
                        ];

                        return response()->json($finalResult);
                    } catch (Exception $err) {
                        return response()->json([
                            'error' => 'An error occurred during getting Tasks. Please try again later.',
                            'msg' => $err->getMessage()
                        ], 500);
                    }
                } else if ($request->query('query') === 'search') {
                    try {
                        $pagination = getPagination($request->query());
                        $task = Tasks::where('name', 'LIKE', '%' . $request->query('key') . '%')
                            ->Where('status', $request->query('status') ? $request->query('status') : 'true')
                            ->with('taskStatus', 'priority', 'milestone', 'project', 'assignedTask.user', 'assignedTask.user:id,firstName,lastName,username')
                            ->orderBy('id', 'desc')
                            ->skip($pagination['skip'])
                            ->take($pagination['limit'])
                            ->get();

                        $taskCount = Tasks::where('name', 'LIKE', '%' . $request->query('name') . '%')
                            ->count();

                        $converted = arrayKeysToCamelCase($task->toArray());
                        $finalResult = [
                            'getAllTask' => $converted,
                            'totalTask' => $taskCount,
                        ];
                        return response()->json($finalResult, 200);
                    } catch (Exception $err) {
                        return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
                    }
                } else if ($request->query('status')) {
                    try {
                        $pagination = getPagination($request->query());
                        $task = Tasks::where('status', $request->query('status'))
                            ->with('taskStatus', 'priority', 'milestone', 'project',)
                            ->orderBy('id', 'desc')
                            ->skip($pagination['skip'])
                            ->take($pagination['limit'])
                            ->get();

                        $taskCount = Tasks::where('status', $request->query('status'))
                            ->count();

                        $converted = arrayKeysToCamelCase($task->toArray());
                        $finalResult = [
                            'getAllTask' => $converted,
                            'totalTask' => $taskCount,
                        ];

                        return response()->json($finalResult);
                    } catch (Exception $err) {
                        return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
                    }
                } else {
                    return response()->json(['error' => 'Invalid query'], 400);
                }
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
        }
    }

    public function getSingleTasks(Request $request, $id)
    {
        try {
            $task = Tasks::with(
                'project',
                'priority',
                'milestone',
                'taskStatus',
                'assignedTask.user:id,firstName,lastName,username',
                'team',
                'team.projectTeamMember',
                'team.projectTeamMember.user:id,username,firstName,lastName'
            )->find($id);
            $converted = arrayKeysToCamelCase($task->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
        }
    }

    public function deleteTasks(Request $request, $id)
    {
        try {
            $task = Tasks::find($id);
            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Tasks. Please try again later.'], 500);
        }
    }
}
