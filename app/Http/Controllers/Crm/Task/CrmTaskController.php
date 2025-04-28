<?php

namespace App\Http\Controllers\Crm\Task;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CrmTaskController extends Controller
{
    //create a crmTask controller method
    public function createTask(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $ids = json_decode($request->getContent(), true);
                $deletedTask = Tasks::destroy($ids);

                $deletedCounted = [
                    'count' => $deletedTask,
                ];

                return response()->json($deletedCounted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many crm task. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);

                $createdManyTask = collect($data)->map(function ($task) {
                    return Tasks::create([
                        'name' => $task['name'],
                        'taskTypeId' => $task['taskTypeId'] ?? null,
                        'priorityId' => $task['priorityId'] ?? null,
                        'crmTaskStatusId' => $task['crmTaskStatusId'] ?? null,
                        'assigneeId' => $task['assigneeId'] ?? null,
                        'contactId' => $task['contactId'] ?? null,
                        'companyId' => $task['companyId']  ?? null,
                        'opportunityId' => $task['opportunityId'] ?? null,
                        'quoteId' => $task['quoteId'] ?? null,
                        'dueDate' => new DateTime($task['dueDate']) ?? null,
                        'notes' => $task['notes'] ?? null,
                    ]);
                });

                $converted = arrayKeysToCamelCase($createdManyTask->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many crm task. Please try again later.'], 500);
            }
        } else {
            try {
                $createdTask = Tasks::create([
                    'name' => $request->input('name'),
                    'taskTypeId' => $request->input('taskTypeId') ?? null,
                    'priorityId' => $request->input('priorityId') ?? null,
                    'crmTaskStatusId' => $request->input('crmTaskStatusId') ?? null,
                    'assigneeId' => $request->input('assigneeId') ?? null,
                    'contactId' => $request->input('contactId') ?? null,
                    'companyId' => $request->input('companyId') ?? null,
                    'opportunityId' => $request->input('opportunityId') ?? null,
                    'quoteId' => $request->input('quoteId') ?? null,
                    'dueDate' => new DateTime($request->input('dueDate'),) ?? null,
                    'notes' => $request->input('notes') ?? null,
                ]);

                $converted = arrayKeysToCamelCase($createdTask->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single crm task. Please try again later.'], 500);
            }
        }
    }

    // get all the crmTask controller method
    public function getAllTask(Request $request): JsonResponse
    {
        if ($request->query('query') === 'all') {
            try {
                $allTask = Tasks::with('taskType:id,taskTypeName', 'priority', 'crmTaskStatus', 'assignee:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName')->orderBy('id', 'desc')->get();

                // $allTask->each(function ($task) {
                //     $task->assignee->fullName = $task->assignee->firstName . " " . $task->assignee->lastName;

                //     $task->contact->fullName = $task->contact->firstName . " " . $task->contact->lastName;
                // });
                $allTask->each(function ($task) {
                    if ($task->assignee) {
                        $task->assignee->fullName = $task->assignee->firstName . ' ' . $task->assignee->lastName;
                    }
                    if ($task->contact) {
                        $task->contact->fullName = $task->contact->firstName . ' ' . $task->contact->lastName;
                    }
                });

                $converted = arrayKeysToCamelCase($allTask->toArray());

                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all crm task. Please try again later.'], 500);
            }
        } elseif ($request->query('query') === 'search') {
            $pagination = getPagination($request->query());

            $allTask = Tasks::with('taskType:id,taskTypeName', 'priority', 'crmTaskStatus', 'assignee:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName')
            
            ->where('status', 'true')
                ->where('name', 'LIKE', '%' . $request->query('key') . '%')
                ->orderBy('id', 'desc')
                ->skip($pagination['skip'])
                ->take($pagination['limit'])
                ->get();

            $total = Tasks::where('status', 'true')
                ->where('name', 'LIKE', '%' . $request->query('key') . '%')
                ->count();

            // $allTask->each(function ($task) {
            //     $task->assignee->fullName = $task->assignee->firstName . " " . $task->assignee->lastName;
            //     $task->contact->fullName = $task->contact->firstName . " " . $task->contact->lastName;
            // });

            $allTask->each(function ($task) {
                if ($task->assignee) {
                    $task->assignee->fullName = $task->assignee->firstName . ' ' . $task->assignee->lastName;
                }
                if ($task->contact) {
                    $task->contact->fullName = $task->contact->firstName . ' ' . $task->contact->lastName;
                }
            });

            $converted = arrayKeysToCamelCase($allTask->toArray());

            $finalResult = [
                'getAllTask' => $converted,
                'totalTask' => $total,
            ];

            return response()->json($finalResult, 200);
        } elseif ($request->query()) {
            try {
                $pagination = getPagination($request->query());

                $allTask = Tasks::when($request->query('status'), function ($query) use ($request) {
                        $query->whereIn('status', explode(',', $request->query('status')));
                    })
                    ->with('taskType:id,taskTypeName', 'priority', 'crmTaskStatus', 'assignee:id,firstName,lastName', 'contact:id,firstName,lastName', 'company:id,companyName')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                
                $total = Tasks::when($request->query('status'), function ($query) use ($request) {
                    $query->whereIn('status', explode(',', $request->query('status')));
                })
                ->count();

                $allTask->each(function ($task) {
                    if ($task->assignee) {
                        $task->assignee->fullName = $task->assignee->firstName . ' ' . $task->assignee->lastName;
                    }
                    if ($task->contact) {
                        $task->contact->fullName = $task->contact->firstName . ' ' . $task->contact->lastName;
                    }
                });

                $converted = arrayKeysToCamelCase($allTask->toArray());

                $finalResult = [
                    'getAllTask' => $converted,
                    'totalTask' => $total
                ];
                return response()->json($finalResult, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting all crm task. Please try again later.'], 500);
            }
        } else {
            return $this->notFound('Invalid query parameter');
        }
    }

    // get a single crmTask controller method
    public function getSingleTask(Request $request, $id): JsonResponse
    {
        try {
            $singleTask = Tasks::where('id', $id)->with('taskType:id,taskTypeName', 'priority', 'crmTaskStatus:id,taskStatusName', 'assignee:id,firstName,lastName', 'contact', 'company:id,companyName', 'opportunity:id,opportunityName', 'quote:id,quoteName')->first();

            if (!$singleTask) {
                return $this->notFound('Crm Task not found!');
            }

            // contact the firstName and lastName
            if ($singleTask->assignee) {
                $singleTask->assignee->fullName = $singleTask->assignee->firstName . ' ' . $singleTask->assignee->lastName;
            }
            if ($singleTask->contact) {
                $singleTask->contact->fullName = $singleTask->contact->firstName . ' ' . $singleTask->contact->lastName;
            }
            $converted = arrayKeysToCamelCase($singleTask->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single crm task. Please try again later.'], 500);
        }
    }

    // update crmTask controller method
    public function updateTask(Request $request, $id): jsonResponse
    {
        try {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            if ($startDate) {
                $startDate = new DateTime($startDate);
                $formattedStartDate = $startDate->format('Y-m-d');
                $request->merge(['startDate' => $formattedStartDate]);
            }
            if ($endDate) {
                $endDate = new DateTime($endDate);
                $formattedEndDate = $endDate->format('Y-m-d');
                $request->merge(['endDate' => $formattedEndDate]);
            }

            $updatedTask = Tasks::where('id', $id)->first();
            $updatedTask->update($request->all());

            $converted = arrayKeysToCamelCase($updatedTask->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single crm task. Please try again later.'], 500);
        }
    }

    // delete or change status crmTask controller method
    public function deleteTask(Request $request, $id): JsonResponse
    {
        try {
            $updatedTask = Tasks::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if ($updatedTask) {
                return response()->json(['message' => 'Crm Task deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete Crm Task!'], 409);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single crm task. Please try again later.'], 500);
        }
    }
}
