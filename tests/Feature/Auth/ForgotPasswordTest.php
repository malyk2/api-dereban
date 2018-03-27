<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Event;
use App\Events\Auth\ForgotPassword as AuthForgotPasswordEvent;

class ForgotPasswordTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/forgotPassword';

    protected $method = 'POST';

    /** @test */
    public function validate_email_required()
    {
        $response = $this->sendJson([
            'email' => '',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['email' => ['The email field is required.']],
        ]);
    }

    /** @test */
    public function validate_email_email()
    {
        $response = $this->sendJson([
            'email' => 'not-valid email',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['email' => ['The email must be a valid email address.']],
        ]);
    }

    /** @test */
    public function validate_email_no_active_user()
    {
        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => false]);
        $response = $this->sendJson([
            'email' => $user->email,
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['email' => ['The selected email is invalid.']],
        ]);
    }

    /** @test */
    public function validate_url_required()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'url' => '',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['url' => ['The url field is required.']],
        ]);
    }

    /** @test */
    public function validate_url_url()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'url' => 'not-url',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['url' => ['The url format is invalid.']],
        ]);
    }

    /** @test */
    public function validate_url_has_hash()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
            'url' => 'https://www.google.com.ua/',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['url' => ['The url not contains {hash} section for replace.']],
        ]);
    }

    /** @test */
    public function success()
    {
        $this->passportInstall();
        $this->fakeEvents();

        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => true]);

        $response = $this->sendJson([
            'email' => $user->email,
            'url' => 'https://www.google.com.ua/{hash}',
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Email for restore password sended.',
            'data' => [],
        ]);

        Event::assertDispatched(AuthForgotPasswordEvent::class);
    }
}
