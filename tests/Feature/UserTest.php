<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        // Create a single App\User instance...
        $this->user = factory(User::class)->create();
    }

    public function testIsNewUserCreated()
    {
        //Was the user created?
        $this->assertDatabaseHas('users', [
            'email' => $this->user->email
        ]);
    }

    public function testLoadLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testLoginForm()
    {
        //try sing in by created user, using login form
        $response = $this->json('POST', '/login', [
            'name' => $this->user->name,
            'password' => 'password'
        ]);
        $this->assertAuthenticated($guard = null);
        $response->assertRedirect('/profile');
    }
}
