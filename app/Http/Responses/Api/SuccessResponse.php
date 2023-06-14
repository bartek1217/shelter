<?php

namespace App\Http\Responses\Api;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse
{
    /** @var DataTranfsorm */
    protected DataTranfsorm $data;

    /** @var int */
    protected int $status = 200;

    /**
     * @param DataTranfsorm $data
     * @return $this
     */
    public function setData(DataTranfsorm $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function toJson(): JsonResponse
    {
        $data = ($this->status === Response::HTTP_NO_CONTENT) ? null : $this->data->toArray();

        return new JsonResponse($data, $this->status);
    }
}
