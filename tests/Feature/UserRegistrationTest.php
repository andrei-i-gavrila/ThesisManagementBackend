<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{

    public function testRegisterCreatesUser()
    {
        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => 'andrei@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSuccessful();

        $this->assertEquals(1, User::count());
    }

    public function testParamsAreRequired()
    {
        $this->post(route('register'), [
            'name' => '',
            'email' => 'andrei@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(422);

        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(422);

        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => 'andrei@gmail.com',
            'password' => '',
            'password_confirmation' => 'password',
        ])->assertStatus(422);

        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => 'andrei@gmail.com',
            'password' => 'password',
            'password_confirmation' => '',
        ])->assertStatus(422);

        $this->assertEquals(0, User::count());
    }

    public function testPasswordNeedsConfirmation()
    {
        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => 'andrei@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'asdasd',
        ])->assertStatus(422);
    }

    public function testEmailParam()
    {
        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => 'andrei',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(422);
        $this->assertEquals(0, User::count());
    }

    public function testUniqueEmail()
    {
        $user = factory(User::class)->create();

        $this->post(route('register'), [
            'name' => 'Andrei 2',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(422);

        $this->assertEquals(1, User::count());
    }

    public function testLoggedInAfterRegister()
    {
        $this->post(route('register'), [
            'name' => 'Andrei',
            'email' => 'andrei@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
            ->assertHeader(Authenticate::TOKEN_FIELD);

        $this->assertAuthenticated();
    }
}
