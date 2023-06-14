<?php

namespace App\Http\Responses;

use App\Http\Responses\Api\DataTranfsorm;
use App\Http\Responses\Api\ErrorResponse;
use App\Http\Responses\Api\SuccessResponse;
use App\Http\Transformers\Transformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ApiResponse
{
    /** @var Request */
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $data
     * @param int $status
     * @param Transformer|null $transformer
     * @return JsonResponse
     */
    public function success(mixed $data, int $status = 200, Transformer $transformer = null): JsonResponse
    {
        $success = new SuccessResponse;
        $success->setData(new DataTranfsorm($data, $this->request, $transformer));
        $success->setStatus($status);

        return $success->toJson();
    }

    /**
     * @param Throwable $e
     * @param string|array $errors
     * @param int|null $status
     * @return JsonResponse
     */
    public function error(Throwable $e, string|array $errors = [], int $status = null): JsonResponse
    {
        $error = new ErrorResponse;
        $error->setError($e);
        $error->setErrors($errors);
        $error->setStatus($status);
        $error->prepare();

        return $error->toJson();
    }
}
