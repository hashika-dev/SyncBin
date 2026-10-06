<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CsrfExpiredTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_mismatch_exception_redirects_to_login_with_status(): void
    {
        Route::post('/test-csrf-trigger', function () {
            throw new TokenMismatchException('CSRF token mismatch.');
        });

        $response = $this->post('/test-csrf-trigger');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Your session expired. Please sign in again.');
    }

    public function test_419_http_exception_redirects_to_login_with_status(): void
    {
        Route::post('/test-419-trigger', function () {
            throw new HttpException(419, 'Page expired.');
        });

        $response = $this->post('/test-419-trigger');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Your session expired. Please sign in again.');
    }
}
