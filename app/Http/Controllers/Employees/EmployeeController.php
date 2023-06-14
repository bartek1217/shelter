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
        $employee = $service->update(Employee::uuidFindOrFail($uuid), $request->all());

        return response()->json($employee, 204);
    }

    /**
     * @param string $uuid
     * @param EmployeeService $service
     * @return JsonResponse
     */
    public function destroy(string $uuid, EmployeeService $service): JsonResponse
    {
        $service->delete(Employee::uuidFindOrFail($uuid));

        return api()->success(null, 204);
    }
}