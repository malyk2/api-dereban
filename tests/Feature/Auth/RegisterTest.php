<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class RegisterTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/register';

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
    public function success_register()
    {
        $this->passportInstall();
        $this->fakeEvents();

        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'User created.',
            'data' => [],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'test@div-art.com',
            'active' => 1,
            'deleted' => 0,
        ]);
    }
}
