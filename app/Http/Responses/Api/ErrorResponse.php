<?php

namespace App\Http\Responses\Api;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorResponse
{
    /** @var Throwable */
    protected Throwable $error;

    /** @var string */
    protected string $message = '';

    /** @var array */
    protected array $errors = [];

    /** @var int */
    protected int $status = 0;

    /**
     * @param Throwable $e
     * @return $this
     */
    public function setError(Throwable $e): static
    {
        $this->error = $e;
        $this->message = $e->getMessage();

        return $this;
    }

    /**
     * @param string|array $errors
     * @return $this
     */
    public function setErrors(string|array $errors): static
    {
        if (! is_array($errors)) {
            $errors = [$errors];
        }

        $this->errors = $errors;

        return $this;
    }

    /**
     * @param int|null $status
     * @return $this
     */
    public function setStatus(int $status = null): static
    {
        $this->status = (int) $status;

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function toJson(): JsonResponse
    {
        $data = [
            'code' => class_basename($this->error),
            'message' => $this->message ?? '',
            'errors' => empty($this->errors) ? null : $this->errors,
        ];

        if (config('app.debug') || 'testing' === config('app.env')) {
            $data['exception'] = [
                'file' => $this->error->getFile(),
                'line' => $this->error->getLine(),
                'trace' => $this->error->getTraceAsString(),
            ];
        }

        return new JsonResponse($data, $this->status);
    }

    /**
     * @return $this
     */
    public function prepare(): static
    {
        $this->status = $this->prepareStatus($this->error, $this->status);
        $this->errors = $this->prepareErrors($this->error, $this->errors);

        return $this;
    }

    /**
     * @param int $status
     * @param Throwable $e
     * @return int
     */
    protected function prepareStatus(Throwable $e, int $status): int
    {
        if ($status === 0) {
            if ($e instanceof HttpExceptionInterface) {
                return $e->getStatusCode();
            }
        }

        $status = match (true) {
            $e instanceof AuthenticationException => 401,
            $e instanceof ValidationException => 422,
            $e instanceof Throwable => $e->getCode(),
            default => 500
        };

        if ($status === 0 || ! is_numeric($status) || $status > 600) {
            $status = 500;
        }

        return $status;
    }

    /**
     * @param Throwable $e
     * @param array $errors
     * @return array
     */
    protected function prepareErrors(Throwable $e, array $errors): array
    {
        return match (true) {
            $e instanceof ValidationException => $e->errors(),
            default => $errors
        };
    }
}
