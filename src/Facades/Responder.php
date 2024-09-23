<?php

namespace Ars\Responder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Responder
 *
 * @method static \Illuminate\Http\JsonResponse respond(array $data = [])
 * @method static \Illuminate\Http\JsonResponse respondPaginate(array $data)
 * @method static self setData(array $data)
 * @method static self setMessage(string $message)
 * @method static self appendData(array $data)
 * @method static self setErrorCode(mixed $errorCode)
 * @method static self setStatusCode(int $statusCode)
 * @method static self created(array $data = [])
 * @method static self updated(array $data = [])
 * @method static self deleted()
 * @method static \Illuminate\Http\JsonResponse ok(mixed $data = [])
 * @method static \Illuminate\Http\JsonResponse paginate(mixed $data)
 * @method static self setLinks(array $links)
 * @method static self setMeta(array $meta)
 * @method static \Illuminate\Http\JsonResponse badRequest()
 * @method static \Illuminate\Http\JsonResponse tooManyRequests()
 * @method static \Illuminate\Http\JsonResponse notFound()
 * @method static \Illuminate\Http\JsonResponse internalError(mixed $exception = null)
 * @method static void validationError(array $errors = [])
 * @method static \Illuminate\Http\JsonResponse unauthorized()
 * @method static \Illuminate\Http\JsonResponse unauthenticated()
 */
class Responder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'responder';
    }
}
