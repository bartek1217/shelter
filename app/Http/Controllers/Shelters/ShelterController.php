<?php

namespace App\Http\Controllers\Shelters;

use App\Http\Controllers\Controller;
use App\Models\Shelter;
use App\Traits\JsonErrorsBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ShelterController extends Controller
{
    use JsonErrorsBuilder;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Shelter::all());
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $shelter = Shelter::uuidFindOrFail($uuid);

        return response()->json($shelter);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json($this->makeJsonError($validator), 422);
        }

        $data = $request->all();
        $data['uuid'] = Str::uuid()->toString();
        $shelter = Shelter::create($data);

        return response()->json($shelter, 201);
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
        ]);
        
        if ($validator->fails()) {
            return response()->json($this->makeJsonError($validator), 422);
        }

        $data = $request->all();
        $shelter = Shelter::uuidFindOrFail($uuid);
        $shelter->fill($data);

        $shelter->save();

        return response()->json($shelter);
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        Shelter::uuidFindOrFail($uuid)->delete();

        return response()->json(null, 204);
    }
}