<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends Controller
{
    /**
     * Return a successful response.
     */
    protected function success(mixed $data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * Return a created response.
     */
    protected function created(mixed $data = null): JsonResponse
    {
        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Return a no content response.
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $status
     * @param array<string, mixed> $errors
     */
    protected function error(string $message, int $status = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        $response = ['message' => $message];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a not found response.
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Return a validation error response.
     *
     * @param array<string, mixed> $errors
     */
    protected function validationError(array $errors): JsonResponse
    {
        return $this->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Return an internal server error response.
     */
    protected function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
