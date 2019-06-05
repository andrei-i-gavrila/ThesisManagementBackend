<?php


namespace App\Auth;


use App\Models\AuthToken;
use Exception;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class TokenGuard implements Guard
{

    use GuardHelpers;

    public const TOKEN_FIELD = "Authorization";
    /**
     * @var Request
     */
    private $request;
    /**
     * @var AuthToken
     */
    private $authToken;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     * @throws Exception
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        return $this->user = $this->authToken() ? $this->authToken()->user : null;
    }

    /**
     * @return AuthToken
     * @throws Exception
     */
    public function authToken()
    {
        if (!is_null($this->authToken)) {
            return $this->authToken;
        }

        $tokenString = $this->request->header(self::TOKEN_FIELD);

        if (!$tokenString) {
            $tokenString = $this->request->input(self::TOKEN_FIELD);
        }

        if (!$tokenString) {
            return null;
        }

        $authToken = AuthToken::with('user')->find($tokenString);

        if (!$authToken) {
            return null;
        }
        if ($authToken->expired()) {
            $this->request->headers->remove(self::TOKEN_FIELD);
            $authToken->delete();
            return null;
        }

        return $this->authToken = $authToken;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    /**
     * @param bool $fromAll
     * @throws Exception
     */
    public function logout($fromAll = false)
    {
        if ($fromAll) {
            AuthToken::whereUserId(Auth::id())->delete();
        } else {
            $this->authToken->delete();
        }
        $this->setUser(null);
        $this->setAuthToken(null);
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function setAuthToken($authToken): void
    {
        $this->authToken = $authToken;
    }
}