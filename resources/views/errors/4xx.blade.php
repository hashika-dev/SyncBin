@include('errors.page', [
    'code' => $exception->getStatusCode(),
    'title' => 'Request could not be completed',
    'message' => 'Something about this request was not right. Please go back and try again.',
])
