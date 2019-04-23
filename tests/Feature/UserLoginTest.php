<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\User;
use Tests\TestCase;

class UserLoginTest extends TestCase
{

    private $user;

    public function testCannotGetUserWhenNotAuthenticated()
    {
        $this->get(route("me"))
            ->assertStatus(401);
    }

    public function testLoginWorks()
    {
        $this->post(route("login"), [
            "email" => $this->user->email,
            "password" => "password"
        ])->assertSuccessful()
            ->assertHeader(Authenticate::TOKEN_FIELD);
    }

    public function testLoginRequiresValidEmail()
    {
        $this->post(route("login"), ["password" => "password"])
            ->assertStatus(422)
            ->assertHeaderMissing(Authenticate::TOKEN_FIELD);


        $this->post(route("login"), ["email" => "email", "password" => "password"])
            ->assertStatus(422)
            ->assertHeaderMissing(Authenticate::TOKEN_FIELD);
    }

    public function testPasswordIsRequired()
    {
        $this->post(route("login"), ["email" => $this->user->email])
            ->assertStatus(422)
            ->assertHeaderMissing(Authenticate::TOKEN_FIELD);
    }

    public function testLoginReturns403()
    {
        $this->post(route("login"), ["email" => "wrong@email.com", "password" => "password"])
            ->assertStatus(403)
            ->assertHeaderMissing(Authenticate::TOKEN_FIELD);

    }

    public function testLoginFeatureWorks()
    {
        $token = $this->post(route("login"), ["email" => $this->user->email, "password" => "password"])
            ->headers->get(Authenticate::TOKEN_FIELD);

        $this->withHeaders([Authenticate::TOKEN_FIELD => $token])->get(route("me"))
            ->assertSuccessful()
            ->assertJson($this->user->fresh()->toArray());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }
}
