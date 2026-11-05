@include('errors.page', [
    'code' => 405,
    'title' => 'Action not allowed',
    'message' => 'This page cannot be opened this way. Use the buttons inside EcoSync instead of typing the address directly.',
])
