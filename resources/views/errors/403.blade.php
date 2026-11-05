@include('errors.page', [
    'code' => 403,
    'title' => 'Access denied',
    'message' => 'Your account does not have permission to open this page. If you think this is a mistake, ask a SuperAdmin.',
])
