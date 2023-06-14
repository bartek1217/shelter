<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\JsonErrorsBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use JsonErrorsBuilder;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $employee = Employee::all();

        return response()->json($employee);
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $employee = Employee::uuidFindOrFail($uuid);

        return response()->json($employee);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'shelter_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($this->makeJsonError($validator), 422);
        }

        $data = $request->all();
        $data['uuid'] = Str::uuid()->toString();
        $employee = Employee::create($data);

        return response()->json($employee, 201);
    }

    /**
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     */
    public function update(string $uuid, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'shelter_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($this->makeJsonError($validator), 422);
        }

        $data = $request->all();
        $employee = Employee::uuidFindOrFail($uuid);
        $employee->fill($data);

        $employee->save();

        return response()->json($employee);
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        Employee::uuidFindOrFail($uuid)->delete();

        return response()->json(null, 204);
    }
}