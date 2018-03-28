<?php

namespace Tests\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ChangeLangTest extends ApiTestCase
{
    use RefreshDatabase;

    protected $path = '/api/v1/user/changeLang';

    protected $method = 'POST';

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['email' => 'test@div-art.com', 'password' => 'password', 'active' => true, 'lang' => 'en']);
    }

    /** @test */
    public function validate_lang_required()
    {
        $response = $this->sendAuthJson($this->user, [
            'lang' => '',
        ]);

        $response->assertStatus(422)->assertJson([
            'validate' => ['lang' => ['The lang field is required.']],
        ]);
    }

    /** @test */
    public function validate_lang_size2()
    {
        $response = $this->sendAuthJson($this->user, [
            'lang' => '111',
        ]);

        $response->assertStatus(422)->assertJson([
            'validate' => ['lang' => ['The lang must be 2 characters.']],
        ]);
    }

    /** @test */
    public function validate_lang_in_config()
    {
        $response = $this->sendAuthJson($this->user, [
            'lang' => 'qq',
        ]);
        $response->assertStatus(422)->assertJson([
            'validate' => ['lang' => ['The selected lang is invalid.']],
        ]);
    }

    /** @test */
    public function success_change_language()
    {
        $this->fakeEvents();
        foreach(config('app.locales') as $lang => $name) {
            if ($lang !== $this->user->lang) {
                $newLang = $lang;
                break;
            }
        }

        $response = $this->sendAuthJson($this->user, [
            'lang' => $newLang,
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'lang' => $newLang,
        ]);
    }
}
