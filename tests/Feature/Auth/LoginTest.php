<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class LoginTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/login';

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
    public function not_success_login_not_active_user()
    {
        $user = factory(User::class)->create(['active' => 0, 'deleted'=>0, 'email' => 'test@div-art.com', 'password' => 'password']);
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);
        $response->assertStatus(400)->assertJson([
            'validate' => null,
            'message' => 'Account is not active.'
        ]);
    }

    /** @test */
    public function not_success_login_deleted_user()
    {
        factory(User::class, 1)->create(['deleted' => 1, 'email' => 'test@div-art.com', 'password' => 'password']);

        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);

        $response->assertStatus(400)->assertJson([
            'validate' => null,
            'message' => 'Email or Password is invalid.'
        ]);
    }

    /** @test */
    public function success_login_active_user()
    {
        $this->passportInstall();
        $this->fakeEvents();

        $userData = ['active' => 1, 'email' => 'test@div-art.com', 'password' => 'password'];

        factory(User::class, 1)->create($userData);

        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Login success.',
        ])->assertJsonStructure(['data']);
    }
}
