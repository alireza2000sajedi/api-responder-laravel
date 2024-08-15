<?php

namespace Ars\Responder\Utils;

use Illuminate\Http\Response as JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Responder
{
    protected int $statusCode = Response::HTTP_OK;
    protected array $data = [];

    /**
     * Prepare and return the response.
     *
     * @return JsonResponse
     */
    public function respond(): JsonResponse
    {
        $response = $this->data;

        // Ensure 'data' and 'success' keys are always present
        if (isset($this->data['data'])) {
            $response['data'] = $this->data['data'];
        }
        if (isset($this->data['success'])) {
            $response['success'] = $this->data['success'];
        }

        return response()->json($response, $this->statusCode);
    }

    /**
     * Set links data.
     *
     * @param mixed $links
     * @return $this
     */
    public function setLinks(mixed $links): static
    {
        $this->data['links'] = $links;
        return $this;
    }

    /**
     * Set meta data.
     *
     * @param mixed $meta
     * @return $this
     */
    public function setMeta(mixed $meta): static
    {
        $this->data['meta'] = $meta;
        return $this;
    }

    /**
     * Append additional data.
     *
     * @param array $data
     * @return $this
     */
    public function appendData(array $data): static
    {
        $this->data['data'] = array_merge($this->data['data'] ?? [], $data);
        return $this;
    }

    /**
     * Set data.
     *
     * @param mixed $data
     * @return $this
     */
    public function setData(mixed $data): static
    {
        $this->data['data'] = $data;
        return $this;
    }

    /**
     * Set error code.
     *
     * @param mixed $error
     * @return $this
     */
    public function setErrorCode(mixed $error): static
    {
        $this->data['error_code'] = $error;
        return $this;
    }

    /**
     * Set a message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        if (!isset($this->data['message'])) {
            $this->data['message'] = $message;
        }
        return $this;
    }

    /**
     * Set success status.
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess(bool $success): static
    {
        $this->data['success'] = $success;
        return $this;
    }

    /**
     * Set HTTP status code.
     *
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Respond with created status.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function created(mixed $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->ok($data);
    }

    /**
     * Respond with updated status.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function updated(mixed $data = []): JsonResponse
    {
        return $this->ok($data);
    }

    /**
     * Respond with deleted status.
     *
     * @return JsonResponse
     */
    public function deleted(): JsonResponse
    {
        return $this->ok();
    }

    /**
     * Respond with bad request status.
     *
     * @return JsonResponse
     */
    public function badRequest(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->setMessage(__('responder::responder.bad_request'))
            ->respond();
    }

    /**
     * Respond with not found status.
     *
     * @return JsonResponse
     */
    public function notFound(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->setSuccess(false)
            ->setMessage(__('responder::responder.not_found'))
            ->respond();
    }

    /**
     * Respond with internal server error status.
     *
     * @param mixed $exception
     * @return JsonResponse
     */
    public function internalError(mixed $exception = null): JsonResponse
    {
        if ($exception) {
            Log::error($exception);
        }
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setSuccess(false)
            ->setMessage(__('responder::responder.internal_error'))
            ->respond();
    }

    /**
     * Respond with unauthorized status.
     *
     * @return JsonResponse
     */
    public function unauthorized(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->setSuccess(false)
            ->setMessage(__('responder::responder.unauthorized'))
            ->respond();
    }

    /**
     * Respond with unauthenticated status.
     *
     * @return JsonResponse
     */
    public function unauthenticated(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->setSuccess(false)
            ->setMessage(__('responder::responder.unauthenticated'))
            ->respond();
    }

    /**
     * Respond with too many requests status.
     *
     * @return JsonResponse
     */
    public function tooManyRequests(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_TOO_MANY_REQUESTS)
            ->setSuccess(false)
            ->setMessage(__('responder::responder.to_many_request'))
            ->respond();
    }

    /**
     * Respond with OK status.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function ok(mixed $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_OK)
            ->setSuccess(true)
            ->setData($data)
            ->respond();
    }

    /**
     * Throw a validation exception with the given errors.
     *
     * @param array $errors
     * @throws ValidationException
     */
    public function validationError(array $errors = [])
    {
        throw ValidationException::withMessages($errors);
    }
}
