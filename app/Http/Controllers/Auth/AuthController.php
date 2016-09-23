<?php namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Validator;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Res;
use Hash;
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
        
        // $this->middleware('guest', ['except' => 'logout']);
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
            'password' => Hash::make($data['password']),
        ]);

        $token = $this->jwt->fromUser($user, [
            'name' => $data['name'],
            'email' => $data['email'],
            'user_id' => $user->id
        ]);


        return Res::success(compact('token'),'Welcome ' . $data['name'] . '. Thank you for registering. Have A nice day!');
    }

    public function getLogin(){

    }

    public function postLogin( Request $request ){

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
                    return Res::success(compact('token'),'You are successfully logined. Have a nice day!');
                }
            }

            catch( JWTException $exception ) {
                return Res::fail( ['error' => 'jwt auth'] ,'JWTAuth Error',400);
            }
        }
   
        return Res::fail( ['error' => 'fake_credentials' ] ,'Credentials are invalids',400);
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