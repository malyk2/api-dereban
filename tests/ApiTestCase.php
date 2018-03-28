<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use App\User;

class ApiTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        app()->setLocale('test');
    }

    protected function sendJson($data = [])
    {
        return $this->json($this->method, $this->path, $data);
    }

    protected function sendAuthJson(User $user, $data = [])
    {
        $this->passportInstall();
        $token = $user->createToken('access_token')->accessToken;

        return $this->withHeaders(['Authorization' => 'Bearer '.$token])->json($this->method, $this->path, $data);
    }

    protected function passportInstall()
    {
        Artisan::call('passport:install');
    }

    protected function fakeEvents()
    {
        Event::fake();
    }
}
