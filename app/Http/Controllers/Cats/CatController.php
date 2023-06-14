<?php

namespace App\Http\Controllers\Cats;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use App\Traits\JsonErrorsBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CatController extends Controller
{
    use JsonErrorsBuilder;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Cat::all());
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $cat = Cat::uuidFindOrFail($uuid);

        return response()->json($cat);
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
        $cat = Cat::create($data);

        return response()->json($cat, 201);
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
        $cat = Cat::uuidFindOrFail($uuid);
        $cat->fill($data);

        $cat->save();

        return response()->json($cat);
    }

    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        Cat::uuidFindOrFail($uuid)->delete();

        return response()->json(null, 204);
    }
}