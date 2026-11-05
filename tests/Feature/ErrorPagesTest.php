<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Error views are only used when debug mode is off, as in production.
        config(['app.debug' => false]);
    }

    public function test_get_logout_shows_custom_405_page(): void
    {
        $this->get('/logout')
            ->assertStatus(405)
            ->assertSee('Error 405')
            ->assertSee('Action not allowed')
            ->assertSee('Go to EcoSync');
    }

    public function test_unknown_page_shows_custom_404_page(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertStatus(404)
            ->assertSee('Page not found');
    }

    public function test_server_error_page_does_not_leak_exception_details(): void
    {
        Route::get('/test-500-trigger', function () {
            throw new \RuntimeException('secret internal detail');
        });

        $this->get('/test-500-trigger')
            ->assertStatus(500)
            ->assertSee('Something went wrong')
            ->assertDontSee('secret internal detail')
            ->assertDontSee('RuntimeException');
    }

    public function test_other_4xx_errors_use_the_fallback_page(): void
    {
        Route::get('/test-410-trigger', fn () => abort(410));

        $this->get('/test-410-trigger')
            ->assertStatus(410)
            ->assertSee('Error 410')
            ->assertSee('Request could not be completed');
    }
}
