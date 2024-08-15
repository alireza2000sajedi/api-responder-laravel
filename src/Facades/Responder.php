<?php

namespace Ars\Responder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Responder
 *
 * @method static \Illuminate\Http\Response respond(array $data = [])
 * @method static \Illuminate\Http\Response respondPaginate(array $data)
 * @method static self setData(array $data)
 * @method static self setMessage(string $message)
 * @method static self appendData(array $data)
 * @method static self setErrorCode(mixed $errorCode)
 * @method static self setStatusCode(int $statusCode)
 * @method static self created(array $data = [])
 * @method static self updated(array $data = [])
 * @method static self deleted()
 * @method static \Illuminate\Http\Response ok(array $data = [])
 * @method static self setLinks(array $links)
 * @method static self setMeta(array $meta)
 * @method static \Illuminate\Http\Response badRequest()
 * @method static \Illuminate\Http\Response tooManyRequests()
 * @method static \Illuminate\Http\Response notFound()
 * @method static \Illuminate\Http\Response internalError(mixed $exception = null)
 * @method static void validationError(array $errors = [])
 * @method static \Illuminate\Http\Response unauthorized()
 * @method static \Illuminate\Http\Response unauthenticated()
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
