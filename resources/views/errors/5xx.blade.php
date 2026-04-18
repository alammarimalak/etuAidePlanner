@php
    $statusCode = $exception->getStatusCode() ?? 500;
    $title = match ($statusCode) {
        503 => 'The service is temporarily unavailable.',
        504 => 'The request took too long to complete.',
        default => 'The server ran into a problem.',
    };
    $description = match ($statusCode) {
        503 => 'This is a server error. The service is temporarily unavailable. Please try again later.',
        504 => 'This is a server error. The request timed out before it could finish. Please try again later.',
        default => 'This is a server error. Something went wrong on our side. Please try again later.',
    };
    $errorType = 'HTTP ' . $statusCode . ' Server Error';
@endphp

@include('errors.layout', compact('statusCode', 'errorType', 'title', 'description'))
