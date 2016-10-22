<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Res;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Events\ResetSuccessEvent;
use Session;
class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $tokens;

    protected $request;

    protected $jwt;
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->request = $request;
    }

    protected function getSendResetLinkEmailSuccessResponse($response){
        
        $token = User::getResetToken( $this->request->get('email') );

        return Res::success(['channelId' => md5($token) ],'The password reset email is sent successfully. Please follow the email to complete your password reset action');
    }

    protected function getSendResetLinkEmailFailureResponse($response){
        return Res::fail($response,'This is email is not registered yet!');
    }

    protected function getResetSuccessResponse($response)
    {
        $user = User::whereEmail( $this->request->get('email') )->first();

        $token = $this->jwt->fromUser($user, [
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id
        ]);

        return Res::success(['token' => $token]);
    }
}
