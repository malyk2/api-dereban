<?php

namespace Tests\Feature;

// use Tests\TestCase;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Support\Facades\Artisan;
use App\User;

class LoginTest extends ApiTestCase
{
    use RefreshDatabase;

    private $path = '/api/v1/login';

    /** @test */
    public function validate_email_required()
    {
        $response = $this->post($this->path, [
            'email' => '',
            'password' => 'password',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function validate_email_email()
    {
        $response = $this->post($this->path, [
            'email' => 'not-valid email',
            'password' => 'password',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function validate_password_required()
    {
        $response = $this->post($this->path, [
            'email' => 'test@div-art.com',
            'password' => '',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function not_success_login_not_active_user()
    {
        factory(User::class, 1)->create(['active' => 0, 'email' => 'test@div-art.com', 'password' => bcrypt('password')]);

        $response = $this->post($this->path, [
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);
        $response->assertStatus(400);
    }

    /** @test */
    public function not_success_login_deleted_user()
    {
        factory(User::class, 1)->create(['deleted' => 1, 'email' => 'test@div-art.com', 'password' => bcrypt('password')]);

        $response = $this->post($this->path, [
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);
        $response->assertStatus(400);
    }

     /** @test */
     public function success_login_active_user()
     {
        $this->passportInstall();
        $this->fakeEvents();

        factory(User::class, 1)->create(['active' => 1, 'email' => 'test@div-art.com', 'password' => bcrypt('password')]);

        $response = $this->post($this->path, [
            'email' => 'test@div-art.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
     }
}
