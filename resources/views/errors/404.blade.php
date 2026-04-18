@php
    $statusCode = 404;
    $errorType = 'HTTP 404 Client Error';
    $title = 'This page could not be found.';
    $description = 'The URL looks incorrect or the page has moved. This is a client error. Please check the address and try again later.';
@endphp

@include('errors.layout', compact('statusCode', 'errorType', 'title', 'description'))
