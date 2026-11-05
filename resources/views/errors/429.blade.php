@include('errors.page', [
    'code' => 429,
    'title' => 'Too many requests',
    'message' => 'You are going a little fast. Please wait a minute, then try again.',
])
