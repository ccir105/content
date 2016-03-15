<?php namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Modules\UserManagement\Entities\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Validator;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Res;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    private $jwt;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required', //|min:6,
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $role = Role::whereName('client')->first();

        $user->attachRole($role);

        return $user;
    }

    public function postLogin( Request $request ){

        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only( 'email', 'password' );

        $user = User::whereEmail( $request->get('email') )->first();
        
        if( $user ) {

            $userData = [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ];
            
            try{
                $token = $this->jwt->attempt( $credentials, $userData );
                
                if( $token  ){
                    return Res::success(compact('token'));
                }
            }

            catch( JWTException $exception ) {
                return Res::fail( ['error' => 'jwt auth'] ,'JWTAuth Error',UNPROCESSED_ENTITY);
            }
        }
    
        return Res::fail( ['error' => 'fake_credentials' ] ,'Credentials are invalids',UNPROCESSED_ENTITY);
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if(!$validator->fails())
        {
            return $this->create($request->all());
        }
        return Res::fail($validator->getMessageBag()->toArray(),'Validation Error',UNPROCESSED_ENTITY);
    }

//        public function pos
}