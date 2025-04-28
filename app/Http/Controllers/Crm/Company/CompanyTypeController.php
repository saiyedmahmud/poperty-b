<?php

namespace App\Http\Controllers\Crm\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyTypeController extends Controller
{
    //create company type
    public function createCompanyType(Request $request): JsonResponse
    {
        if ($request->query('query') === 'deletemany') {
            try {
                $data = json_decode($request->getContent(), true);
                $deleteMany = CompanyType::destroy($data);
                return response()->json(['count' => $deleteMany], 200);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during deleting many company type. Please try again later.'], 500);
            }
        } else if ($request->query('query') === 'createmany') {
            try {
                $data = json_decode($request->getContent(), true);
                foreach ($data as $item) {
                    CompanyType::insertOrIgnore($item);
                }
                $converted = arrayKeysToCamelCase($data->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating many company type. Please try again later.'], 500);
            }
        } else {
            try {
                $createdCompany = CompanyType::create([
                    'companyTypeName' => $request->input('companyTypeName'),
                ]);
                $converted = arrayKeysToCamelCase($createdCompany->toArray());
                return response()->json($converted, 201);
            } catch (Exception $err) {
                return response()->json(['error' => 'An error occurred during creating a single company type. Please try again later.'], 500);
            }
        }
    }

    //get all company type
    public function getAllCompanyType(Request $request): JsonResponse
    {
        try {
            $allCompanyType = CompanyType::with('company')->orderBy('id', 'desc')->get();
            $converted = arrayKeysToCamelCase($allCompanyType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting all company type. Please try again later.'], 500);
        }
    }

    //get single company type
    public function getSingleCompanyType(Request $request, $id): JsonResponse
    {
        try {
            $singleCompanyType = CompanyType::with('company')->find($id);
            $converted = arrayKeysToCamelCase($singleCompanyType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during getting a single company type. Please try again later.'], 500);
        }
    }

    //update company type
    public function updateCompanyType(Request $request, $id): JsonResponse
    {
        try {
            $singleCompanyType = CompanyType::find($id);
            $singleCompanyType->companyTypeName = $request->input('companyTypeName');
            $singleCompanyType->save();
            $converted = arrayKeysToCamelCase($singleCompanyType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during updating a single company type. Please try again later.'], 500);
        }
    }

    //delete company type
    public function deleteCompanyType(Request $request, $id): JsonResponse
    {
        try {
            $singleCompanyType = CompanyType::find($id);
            $singleCompanyType->delete();
            $converted = arrayKeysToCamelCase($singleCompanyType->toArray());
            return response()->json($converted, 200);
        } catch (Exception $err) {
            return response()->json(['error' => 'An error occurred during deleting a single company type. Please try again later.'], 500);
        }
    }
}
