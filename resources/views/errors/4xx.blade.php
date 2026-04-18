@php
    $statusCode = $exception->getStatusCode() ?? 400;
    $isNotFound = $statusCode === 404;
    $title = $isNotFound ? 'This page could not be found.' : 'This request could not be completed.';
    $description = match ($statusCode) {
        400 => 'This is a client error. The request was not accepted. Please check your input and try again later.',
        401 => 'This is a client error. You need to sign in before continuing. Please try again later.',
        403 => 'This is a client error. You do not have permission to open this page. Please try again later.',
        404 => 'The URL looks incorrect or the page has moved. This is a client error. Please check the address and try again later.',
        419 => 'This page expired before the request was completed. This is a client error. Please try again later.',
        429 => 'Too many requests were sent in a short time. This is a client error. Please wait and try again later.',
        default => 'This is a client error. Please try again later.',
    };
    $errorType = 'HTTP ' . $statusCode . ' Client Error';
@endphp

@include('errors.layout', compact('statusCode', 'errorType', 'title', 'description'))
