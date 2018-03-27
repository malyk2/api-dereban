<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Event;
use App\Events\Auth\RegisterActivate as AuthRegisterActivateEvent;

class RegisterActivateTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/registerActivate';

    protected $method = 'POST';

    /** @test */
    public function validate_email_required()
    {
        $response = $this->sendJson([
            'email' => '',
            'password' => 'password',
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
            'password' => 'password',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['email' => ['The email must be a valid email address.']],
        ]);
    }

    /** @test */
    public function validate_email_unique()
    {
        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password']);

        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['email' => ['The email has already been taken.']],
        ]);
    }

    /** @test */
    public function validate_password_required()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => '',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['password' => ['The password field is required.']],
        ]);
    }

    /** @test */
    public function validate_password_min6()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => '11111',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['password' => ['The password must be at least 6 characters.']],
        ]);
    }

    /** @test */
    public function validate_url_required()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
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
            'password' => 'password',
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
    public function success_register()
    {
        $this->passportInstall();
        $this->fakeEvents();

        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
            'url' => 'https://www.google.com.ua/{hash}',
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'User created. Email to activate account sended.',
            'data' => [],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'test@div-art.com',
            'active' => 0,
            'deleted' => 0,
        ]);

        Event::assertDispatched(AuthRegisterActivateEvent::class);
    }
}
