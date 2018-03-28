<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ActivateTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/activate';

    protected $method = 'POST';

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
        ]);

        $response->assertStatus(400)->assertJson([
            'validate' => null,
            'message' => 'Invalid link.'
        ]);
    }

    /** @test */
    public function send_hash_active_user()
    {
        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => true]);

        $response = $this->sendJson([
            'hash' => $user->getHashActivate(),
        ]);
        $response->assertStatus(405)->assertJson([
            'validate' => null,
            'message' => "Account hasn't status 'new'.",
        ]);
    }

    /** @test */
    public function success_activate()
    {
        $this->passportInstall();
        $this->fakeEvents();

        $user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password']);

        $response = $this->sendJson([
            'hash' => $user->getHashActivate(),
        ]);
        $response->assertStatus(200)->assertJson([
            'data' => [],
            'message' => "Account activated.",
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@div-art.com',
            'active' => 1,
            'deleted' => 0,
        ]);
    }
}
