<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ChangePasswordTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/changePassword';

    protected $method = 'POST';

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
    public function validate_password_confirmation()
    {
        $response = $this->sendJson([
            'email' => 'test@div-art.com',
            'password' => '111111',
            'password_confirmation' => '222222222',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['password' => ['The password confirmation does not match.']],
        ]);
    }

    /** @test */
    public function validate_hash_required()
    {
        $response = $this->sendJson([
            'hash' => '',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['hash' => ['The hash field is required.']],
        ]);
    }

    /** @test */
    public function send_wrong_hash()
    {
        $response = $this->sendJson([
            'hash' => 'wrong hash',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(400)->assertJson([
            'validate' => null,
            'message' => 'Invalid link.'
        ]);
    }

    /** @test */
    public function send_hash_not_active_user()
    {
        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => false]);

        $response = $this->sendJson([
            'hash' => $user->getHashForgotPassword(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(405)->assertJson([
            'validate' => null,
            'message' => "Account not active.",
        ]);
    }

    /** @test */
    public function success_activate()
    {
        $this->passportInstall();
        $this->fakeEvents();

        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => true]);

        $response = $this->sendJson([
            'hash' => $user->getHashForgotPassword(),
            'password' => 'password-new',
            'password_confirmation' => 'password-new',
        ]);
        $response->assertStatus(200)->assertJson([
            'data' => [],
            'message' => "Password changed.",
        ]);

        $this->assertTrue(auth()->attempt(['email' => 'test@div-art.com', 'password' => 'password-new']));
    }
}
