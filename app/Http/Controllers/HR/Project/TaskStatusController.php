<?php

namespace App\Http\Controllers\HR\Project;

use App\Http\Controllers\Controller;
use App\Models\TaskStatus;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    //create a new task status
    public function createTaskStatus(Request $request): JsonResponse
    {
        try {
            $createTaskStatus = TaskStatus::create([
                'projectId' => $request->projectId,
                'name' => $request->name,
            ]);

            $converted = arrayKeysToCamelCase($createTaskStatus->toArray());
            return response()->json($converted, 201);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during creating Task Status. Please try again later.'], 500);
        }
    }

    //get all task status
    public function getAllTaskStatus(Request $request): JsonResponse
    {
        //get all and true false
        if ($request->query('query') === "all") {
            try {
                $taskStatus = TaskStatus::orderBy('id', 'asc')->get();

                $converted = arrayKeysToCamelCase($taskStatus->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Task Status. Please try again later.'], 500);
            }
        } else if ($request->query('status')) {
            try {
                $taskStatus = TaskStatus::where('status', $request->query('status'))->orderBy('id', 'asc')->get();

                $converted = arrayKeysToCamelCase($taskStatus->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Task Status. Please try again later.'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid query!'], 400);
        }
    }

    //get single task status
    public function getSingleTaskStatus(Request $request): JsonResponse
    {
        try {
            $taskStatus = TaskStatus::with('tasks')->where('id', $request->id)->first();

            if ($taskStatus) {
                $converted = arrayKeysToCamelCase($taskStatus->toArray());
                return response()->json($converted, 200);
            } else {
                return response()->json(['error' => 'Task status not found'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Task Status. Please try again later.'], 500);
        }
    }

    //get taskStatus by projectId controller. This controller will return a taskStatus by projectId.
    public function getTaskStatusByProjectId(Request $request, $id): JsonResponse
    {
        try {
            $taskStatus = TaskStatus::with('project', 'project.milestone', 'tasks', 'tasks.priority', 'tasks.taskStatus', 'tasks.assignedTask')->where('projectId', $id)->orderBy('id', 'asc')->get();

            if ($taskStatus) {
                $converted = arrayKeysToCamelCase($taskStatus->toArray());
                return response()->json($converted, 200);
            } else {
                return response()->json(['error' => 'Task status not found'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => $err->getMessage()], 500);
            return response()->json(['error' => 'An error occurred during getting Task Status. Please try again later.'], 500);
        }
    }

    //update task status
    public function updateTaskStatus(Request $request, $id): JsonResponse
    {
        try {
            $taskStatus = TaskStatus::find($id);
            if ($taskStatus) {
                $taskStatus->update($request->all());

                $converted = arrayKeysToCamelCase($taskStatus->toArray());
                return response()->json($converted, 200);
            } else {
                return response()->json(['error' => 'Task status not found'], 404);
            }
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating Task Status. Please try again later.'], 500);
        }
    }

    //delete task status
    public function deleteTaskStatus(Request $request): JsonResponse
    {
        try {
            $taskStatus = TaskStatus::where('id', $request->id)->with('tasks')->first();
            if ($taskStatus->tasks->count() > 0) {
                return response()->json(['error' => 'Task status has task. Please delete task first.'], 400);
            }
            if ($taskStatus) {
                $taskStatus->delete();
                return response()->json(['message' => 'Task status deleted'], 200);
            } else {
                return response()->json(['error' => 'Task status not found'], 404);
            }
        } catch (Exception $err) {

            return response()->json(['error' => 'An error occurred during deleting Task Status. Please try again later.'], 500);
        }
    }
}
