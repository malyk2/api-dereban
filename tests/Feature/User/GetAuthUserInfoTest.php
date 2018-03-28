<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class GetAuthUserInfoTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/user/getAuthUserInfo';

    protected $method = 'GET';

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => true, 'lang' => 'en']);
    }

    /** @test */
    public function success()
    {
        $response = $this->sendAuthJson($this->user, []);

        $response->assertStatus(200)->assertJson([
            'data' => ['user' => $this->user->toArray()],
        ]);
    }
}
