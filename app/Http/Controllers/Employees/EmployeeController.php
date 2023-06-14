<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employees\EmployeeStoreRequest;
use App\Http\Requests\Employees\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Services\Employee\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
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
     * @param EmployeeStoreRequest $request
     * @return JsonResponse
     */
    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        $data = $request->all();
        $data['uuid'] = Str::uuid()->toString();
        $employee = Employee::create($data);

        return response()->json($employee, 201);
    }

    /**
     * @param string $uuid
     * @param EmployeeUpdateRequest $request
     * @return JsonResponse
     */
    public function update(string $uuid, EmployeeUpdateRequest $request): JsonResponse
    {
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