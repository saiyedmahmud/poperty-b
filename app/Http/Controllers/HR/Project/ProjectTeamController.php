<?php

namespace App\Http\Controllers\HR\Project;

use App\Http\Controllers\Controller;
use App\Models\ProjectTeam;
use App\Models\ProjectTeamMember;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectTeamController extends Controller
{
    //create a new project team
    public function createProjectTeam(Request $request): JsonResponse
    {
        try {
            $projectTeam = ProjectTeam::create([
                'projectId' => $request->projectId,
                'projectTeamName' => $request->projectTeamName ?? null,
            ]);


            //now create projectTeamMember, projectTeamMember is an array
            foreach ($request->projectTeamMember as $item) {
                ProjectTeamMember::create([
                    'projectTeamId' => $projectTeam->id,
                    'userId' => $item,
                ]);
            }

            $converted = arrayKeysToCamelCase($projectTeam->toArray());
            return response()->json($converted, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred during creating Project Team. Please try again later.'], 500);
        }
    }

    //get all project teams
    public function getAllProjectTeams(Request $request): JsonResponse
    {
        if ($request->query('query') == 'all') {
            try {
                $projectTeams = ProjectTeam::orderBy('id', 'asc')
                    ->where('status', 'true')
                    ->get();
                $converted = arrayKeysToCamelCase($projectTeams->toArray());
                return response()->json($converted, 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred during getting Project Team. Please try again later.'], 500);
            }
        } else if ($request->query('status')) {

            try {
                $pagination = getPagination($request->query());
                $projectTeams = ProjectTeam::where('status', $request->query('status'))
                    ->orderBy('id', 'asc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();

                $converted = arrayKeysToCamelCase($projectTeams->toArray());
                $aggregation = [
                    'getAllProjectTeam' => $converted,
                    'totalProjectTeam' => ProjectTeam::where('status', $request->query('status'))
                        ->count(),
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred during getting Project Team. Please try again later.'], 500);
            }
        }else if($request->query('query') == 'search'){
            try {
                $pagination = getPagination($request->query());
                $projectTeams = ProjectTeam::where('projectTeamName', 'like', '%' . $request->query('key') . '%')
                    ->orWhere('id', 'like', '%' . $request->query('key') . '%')
                    ->orderBy('id', 'asc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                $count = ProjectTeam::where('projectTeamName', 'like', '%' . $request->query('key') . '%')
                    ->orWhere('id', 'like', '%' . $request->query('key') . '%')
                    ->count();
                    
                $converted = arrayKeysToCamelCase($projectTeams->toArray());
                $aggregation = [
                    'getAllProjectTeam' => $converted,
                    'totalProjectTeam' => $count,
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred during getting Project Team. Please try again later.'], 500);
            }

        } else if($request->query()){
            try {
                $pagination = getPagination($request->query());
                
                $projectTeams = ProjectTeam::where('status', 'true')
                ->orderBy('id', 'asc')
                    ->skip($pagination['skip'])
                    ->take($pagination['limit'])
                    ->get();
                $converted = arrayKeysToCamelCase($projectTeams->toArray());
                $aggregation = [
                    'getAllProjectTeam' => $converted,
                    'totalProjectTeam' => ProjectTeam::count(),
                ];

                return response()->json($aggregation, 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred during getting Project Team. Please try again later.'], 500);
            }
        }
         else {
            return response()->json(['error' => 'Invalid query!'], 400);
        }
    }

    //get single project team
    public function getSingleProjectTeam(Request $request): JsonResponse
    {
        try {
            $projectTeam = ProjectTeam::with('project.projectManager:id,firstName,lastName', 'projectTeamMember.user:id,firstName,lastName')->find($request->id);
            $converted = arrayKeysToCamelCase($projectTeam->toArray());
            return response()->json($converted, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred during getting a single Project Team. Please try again later.'], 500);
        }
    }

    //get projectTeam by project id controller
    public function getProjectTeamByProjectId(Request $request): JsonResponse
    {
        try {
            $projectTeam = ProjectTeam::with('project', 'projectTeamMember.user:id,firstName,lastName')->where('projectId', $request->id)->get();
            $converted = arrayKeysToCamelCase($projectTeam->toArray());
            return response()->json($converted, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred during getting Project Team. Please try again later.'], 500);
        }
    }

    //update project team
    public function updateProjectTeam(Request $request, $id): JsonResponse
    {

        if ($request->query('query') == 'all') {
            try {
                $projectTeam = ProjectTeam::findOrFail($id);

                if ($request->projectTeamName) {
                    $projectTeam->update([
                        'projectTeamName' => $request->projectTeamName,
                    ]);
                }

                //now update projectTeamMember, projectTeamMember is an array
                foreach ($request->projectTeamMember as $item) {
                    ProjectTeamMember::updateOrCreate([
                        'projectTeamId' => $projectTeam->id,
                        'userId' => $item,
                    ]);
                }
                $converted = arrayKeysToCamelCase($projectTeam->toArray());
                return response()->json($converted, 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred during updating Project Team. Please try again later.'], 500);
            }
        } else {
            try {
                $projectTeam = ProjectTeam::where('id', $id)->update([
                    'status' => $request['status'],
                ]);

                if (!$projectTeam) {
                    return response()->json([
                        'error' => 'Failed to update project team status'
                    ], 404);
                }
                return response()->json([
                    'message' => 'Project team updated successfully'
                ], 200);
            } catch (Exception $e) {
                return response()->json(['error' => 'An error occurred during updating Project Team. Please try again later.'], 500);
            }
        }
    }

    //delete project team
    public function deleteProjectTeam(Request $request, $id): JsonResponse
    {
        try {
            $projectTeam = ProjectTeam::where('id', $id)->first();

            $projectTeam->status = $request->input('status');
            $projectTeam->save();

            if (!$projectTeam) {
                return response()->json(['error' => 'Failed to delete Project Team'], 404);
            }

            return response()->json(['message' => 'Project team deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred during deleting Project Team. Please try again later.'], 500);
        }
    }
}
