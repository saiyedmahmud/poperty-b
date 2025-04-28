<?php

namespace App\Http\Controllers\HR\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TaskStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ProjectController extends Controller
{
    //create project
    public function createProject(Request $request): JsonResponse
    {
        if ($request->query('query') === "createmany") {
            try {
                $data = json_decode($request->getContent(), true);
                foreach ($data as $item) {
                    Project::insertOrIgnore($item);
                }

                return response()->json(["count" => count(json_decode($data))], 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating Project. Please try again later.'], 500);
            }
        } else {
            try {
                $createProject = Project::create([
                    'projectManagerId' => $request->input('projectManagerId') ?? null,
                    'name' => $request->input('name'),
                    'contactId' => $request->input('contactId') ?? null,
                    'priorityId' => $request->input('priorityId') ?? null,
                    'projectValue' => $request->input('projectValue') ?? null,
                    'projectStatus' => $request->input('projectStatus') ?? null,
                    'startDate' => Carbon::parse($request->input('startDate')) ?? null,
                    'endDate' => Carbon::parse($request->input('endDate')) ?? null,
                    'description' => $request->input('description') ?? null,
                ]);

                $status = [
                    'TODO',
                    'IN PROGRESS',
                    'DONE',
                ];
                //create task status
                foreach ($status as $item) {
                    TaskStatus::create([
                        'projectId' => $createProject->id,
                        'name' => $item,
                    ]);
                }
                $converted = arrayKeysToCamelCase($createProject->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return $this->badRequest($err->getMessage());
            }
        }
    }

    //get all projects
    public function getAllProjects(Request $request): JsonResponse
    {
        if ($request->query('query') === "all") {
            try {
                $projects = Project::with('projectManager:id,firstName,lastName', 'contact', 'priority:id,name')
                    ->orderBy('id', 'desc')
                    ->get();
                
                $converted = arrayKeysToCamelCase($projects->toArray());
                return response()->json($converted, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Project. Please try again later.'], 500);
            }
        } else if ($request->query('status')) {
            try {
                $pagination = getPagination($request->query());
                $projects = Project::with('projectManager:id,firstName,lastName', 'contact', 'priority:id,name')
                    ->when($request->query('status'), function ($query) use ($request) {
                        $status = explode(',', $request->query('status'));
                        return $query->whereIn('status', $status);
                    })
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $converted = arrayKeysToCamelCase($projects->toArray());
                $aggregation = [
                    'getAllProject' => $converted,
                    'totalProject' => Project::where('status', $request->query('status'))
                        ->count(),
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Project. Please try again later.'], 500);
            }
        } elseif ($request->query('value') === 'all') {
            try {
                $pagination = getPagination($request->query());
                $projects = Project::with('projectManager:id,firstName,lastName', 'contact', 'priority:id,name')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $converted = arrayKeysToCamelCase($projects->toArray());
                $aggregation = [
                    'getAllProject' => $converted,
                    'totalProject' => Project::count(),
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Project. Please try again later.'], 500);
            }
        }else if($request->query('query') === "search"){
            try {
                
                $pagination = getPagination($request->query());
                $projects = Project::with('projectManager:id,firstName,lastName', 'contact', 'priority:id,name')
                    ->where('name', 'like', '%' . $request->query('key') . '%')
                    ->orWhere('id', 'like', '%' . $request->query('key') . '%')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $count = Project::where('name', 'like', '%' . $request->query('key') . '%')
                    ->where('id', 'like', '%' . $request->query('key') . '%')
                    ->count();
                $converted = arrayKeysToCamelCase($projects->toArray());
                $aggregation = [
                    'getAllProject' => $converted,
                    'totalProject' => $count,
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Project. Please try again later.'], 500);
            }
        }
        else if($request->query()){
            try {
                $pagination = getPagination($request->query());
                $projects = Project::with('projectManager:id,firstName,lastName', 'contact', 'priority:id,name')
                    ->orderBy('id', 'desc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $converted = arrayKeysToCamelCase($projects->toArray());
                $aggregation = [
                    'getAllProject' => $converted,
                    'totalProject' => Project::count(),
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during getting Project. Please try again later.'], 500);
            }

        } 
        else {
            return response()->json(['error' => 'Invalid query!'], 400);
        }
    }

    //getSingle project
    public function getSingleProject(Request $request, $id): JsonResponse
    {
        try {
            $project = Project::with('projectManager:id,firstName,lastName', 'milestone', 'projectTeam.projectTeamMember.user:id,firstName,lastName', 'tasks.taskStatus', 'contact', 'priority:id,name')
                ->find($id);

            $converted = arrayKeysToCamelCase($project->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting Project. Please try again later.'], 500);
        }
    }

    //update project
    public function updateProject(Request $request, $id): JsonResponse
    {
        try {
            $startDate =  Carbon::parse($request->input('startDate'));
            $endDate = Carbon::parse($request->input('endDate'));

            $request->merge([
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);

            $project = Project::where('id', $id)->update($request->all());

            if (!$project) {
                return response()->json(['error' => "failed to update Project!"], 404);
            }
            return response()->json(['message' => 'Project Updated Successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating Project. Please try again later.'], 500);
        }
    }

    //delete project
    public function deleteProject(Request $request, $id): JsonResponse
    {
        try {
            //update the status
            $project = Project::where('id', $id)->update([
                'status' => $request->input('status'),
            ]);

            if (!$project) {
                return response()->json(['error' => 'Failed to delete Project'], 404);
            }

            return response()->json(['message' => 'Project deleted successfully'], 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting Project. Please try again later.'], 500);
        }
    }
}
