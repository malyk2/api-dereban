<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    private $path = '/api/v1/login';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->post($this->path, [
            'email' => 'id@div-art.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200);
    }
}
