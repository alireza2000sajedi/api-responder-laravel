# API Responder - Laravel Response

**ars/api-responder-laravel** is a Laravel package designed to streamline the creation of standardized API responses. It provides a user-friendly interface for managing API responses, including common status codes and error messages, making API development in Laravel more efficient and consistent.

## Features

- Standardized response structure for successful and error responses.
- Customizable response data with support for links and metadata.
- Easy integration with Laravel through a facade and service provider.

## Installation

You can install the library via Composer. Run the following command:

```bash
composer require ars/api-responder-laravel
```
# Usage
## Basic Response
You can use the Responder facade to create standardized API responses.

### Example:
```php
use Responder;

return Responder::ok($response);
return Responder::setMessage('success')->ok($response);
return Responder::tooManyRequests();
return Responder::notFound();
return Responder::unAuthorized();

$data = $this->repository->paginate();
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
return Responder::setMeta($meta)->setLinks($links)->ok($data->items());

return Responder::setStatusCode(500)->setMessage($e->getMessage())->respond();

```

```json
{
  "message": "Operation successful!",
  "success": true,
  "data": {
    "accessToken": "28|PDVEA7z6mUPcbbIybkpiMPJMvy3TLtuWOguiGDGn13c67491",
    "tokenType": "Bearer",
    "expiresIn": null
  }
}
```

## Additional Methods
- `ok(array $data)`: Return a response 200.
- `respond(array $data)`: Prepare and return a response.
- `setData(array $data)`: Set the response data.
- `setMessage(string $message)`: Set a message for the response.
- `appendData(array $data)`: Append additional data to the response.
- `setErrorCode(mixed $errorCode)`: Set an error code for the response.
- `setStatusCode(int $statusCode)`: Set the HTTP status code for the response.
- `setLinks(array $links)`: Set pagination or other links.
- `setMeta(array $meta)`: Set metadata for the response.

## Messages
The library supports various status messages:

#### If a message key is provided, use `setMessage(string $message)` to set the message.

- **success:** "Operation successful!"
- **error:** "Operation encountered an error!"
- **validation_error:** "Validation failed!"
- **internal_error:** "Internal server error!"
- **created:** "has been created."
- **updated:** "has been updated."
- **deleted:** "has been deleted."
- **send:** "has been sent."
- **before_posted:** "has already been posted."
- **expire:** "has expired."
- **not_valid:** "is not valid."
- **used:** "has already been used."
- **re_send:** "has been resent."
- **unauthenticated:** "User is not authenticated!"
- **unauthorized:** "User is not authorized to access this resource!"
- **not_found:** "Resource not found!"
- **bad_request:** "Bad request!"
- **to_many_request:** "Too many requests!"


## Contributing
Contributions are welcome! Please adhere to the following guidelines:

1. Fork the repository.
2. Create a feature branch (git checkout -b feature/my-new-feature).
3. Commit your changes (git commit -am 'Add new feature').
4. Push to the branch (git push origin feature/my-new-feature).
5. Open a Pull Request.

## License
This library is licensed under the MIT License.

## Contact
For support or questions, please open an issue on GitHub.
