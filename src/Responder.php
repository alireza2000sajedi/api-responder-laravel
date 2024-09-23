<?php

namespace Ars\Responder;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
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
        $response = [];

        // Set success, message, and data fields first
        if (isset($this->data['success'])) {
            $response['success'] = $this->data['success'];
        }
        if (isset($this->data['message'])) {
            $response['message'] = $this->data['message'];
        }
        if (isset($this->data['data'])) {
            $response['data'] = $this->data['data'];
        }

        // Set meta and links fields after
        if (isset($this->data['meta'])) {
            $response['meta'] = $this->data['meta'];
        }
        if (isset($this->data['links'])) {
            $response['links'] = $this->data['links'];
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
            if (Lang::has("responder::responder.$message")){
                $message = Lang::get("responder::responder.$message");
            }
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
        return $this->setStatusCode(Response::HTTP_CREATED)
            ->setMessage('created')
            ->ok($data);
    }

    /**
     * Respond with updated status.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function updated(mixed $data = []): JsonResponse
    {
        return $this->setMessage('updated')
            ->ok($data);
    }

    /**
     * Respond with deleted status.
     *
     * @return JsonResponse
     */
    public function deleted(): JsonResponse
    {
        return $this->setMessage('deleted')
            ->ok();
    }

    /**
     * Respond with bad request status.
     *
     * @return JsonResponse
     */
    public function badRequest(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->setMessage('bad_request')
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
            ->setMessage('not_found')
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
            ->setMessage('internal_error')
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
            ->setMessage('unauthorized')
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
            ->setMessage('unauthenticated')
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
            ->setMessage('to_many_request')
            ->respond();
    }

    /**
     * Respond with OK status.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function ok(array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_OK)
            ->setSuccess(true)
            ->setMessage('success')
            ->setData($data)
            ->respond();
    }

    /**
     * Respond paginate data.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public function paginate(mixed $data): JsonResponse
    {
        $links = [
            'next' => $data->nextPageUrl(),
            'prev' => $data->previousPageUrl(),
            'path' => $data->path(),
        ];

        $meta = [
            'total'        => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page'     => $data->perPage(),
        ];

        $items = $data->items();

        return $this->setMeta($meta)->setLinks($links)->ok($items);

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
