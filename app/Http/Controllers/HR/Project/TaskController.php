<?php

namespace App\Http\Controllers\HR\Project;

use DateTime;
use Exception;
use App\Models\Tasks;
use App\Models\AssignedTask;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    //create task
    // public function createTask(Request $request): JsonResponse
    // {
    //     try {
    //         $createTask = Tasks::create([
    //             'projectId' => $request->projectId,
    //             'milestoneId' => $request->milestoneId,
    //             'priorityId' => $request->priorityId,
    //             'taskStatusId' => $request->taskStatusId,
    //             'name' => $request->name,
    //             'startDate' => $request->startDate,
    //             'endDate' => $request->endDate,
    //             'completionTime' => $request->completionTime,
    //             'description' => $request->description,
    //             'type' => 'project',
    //         ]);

    //         //create assigned task
    //         foreach ($request->assignedTask as $item) {
    //             AssignedTask::create([
    //                 'taskId' => $createTask->id,
    //                 'userId' => $item,
    //             ]);
    //         }

    //         $converted = arrayKeysToCamelCase($createTask->toArray());
    //         return response()->json($converted, 201);
    //     } catch (Exception $err) {
    //         return response()->json(['error' => 'An error occurred during creating Tasks. Please try again later.'], 500);
    //     }
    // }

    //get all task
    public function getAllTask(Request $request): JsonResponse
    {
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
        } else if ($request->query('status')) {
            try {
                $pagination = getPagination($request->query());
                $task = Tasks::where('status', $request->query('status'))
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

    //get single task
    public function getSingleTask($id): JsonResponse
    {
        try {
            $task = Tasks::with('project', 'milestone', 'priority', 'taskStatus', 'assignedTask.user:id,firstName,lastName,username', 'project.projectTeam', 'project.projectTeam.projectTeamMember', 'project.projectTeam.projectTeamMember.user:id,username,firstName,lastName', 'team', 'team.projectTeamMember','team.projectTeamMember.user:id,username,firstName,lastName' )->where('id', $id)->first();
            if (!$task) {
                return response()->json(['error' => 'Tasks not found'], 404);
            }

            $converted = arrayKeysToCamelCase($task->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Tasks. Please try again later.'], 500);
        }
    }

    //update task
    public function updateTask(Request $request, $id): JsonResponse
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
            $task = Tasks::find($id);
            if (!$task) {
                return response()->json(['error' => 'Tasks not found'], 404);
            }
            $task->update($request->all());
            $task->save();

            if($request->assignedTask) {
                AssignedTask::where('taskId', $id)->delete();
                foreach ($request->assignedTask as $item) {
                    AssignedTask::create([
                        'taskId' => $id,
                        'userId' => $item,
                    ]);
                }
            }
            $converted = arrayKeysToCamelCase($task->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating Tasks. Please try again later.'], 500);
        }
    }

    //delete task
    public function deleteTask($id): JsonResponse
    {
        try {
            $task = Tasks::find($id);
            if (!$task) {
                return response()->json(['error' => 'Tasks not found'], 404);
            }
            $task->delete();

            return response()->json(['message' => 'Tasks deleted successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Tasks. Please try again later.'], 500);
        }
    }
}
