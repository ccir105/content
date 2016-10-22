<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UniqueEmailRequest;
use App\Advice;
use App\UserAdvice;
use Auth;
use App\User;
use Res;
use App\Http\Requests\AddNewAdvice;
use App\Http\Requests\AddGlobalAdvice;
use App\Http\Requests\ChangeInfoRequest;
use App\Http\Requests\LoginAfterResetRequest;
use Session;
use Tymon\JWTAuth\JWTAuth;
use App\Events\GlobalAdviceNotifier;
use DB;

class MainController extends Controller
{   
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function isUniqueEmail(UniqueEmailRequest $request)
    {
   		return Res::success( ['exists' => User::whereEmail( $request->get('email') ) ->first() ||  false ] );	
    }

    public function validateResetToken(Request $request)
    {
        $token = DB::table('password_resets')->where('token', $request->get('token'))->whereEmail($request->get('email'))->first();
        return $token ? Res::success([]) : Res::fail([],'The reset token is malformed');
    }

    public function saveAdvice(AddNewAdvice $request)
    {
    	$advice = new Advice;
    	$advice->fill($request->all());
    	$advice->type = $request->get('isGlobal');
    	$advice->user_id = Auth::user()->id;
    	
    	if( $advice->save() )
    	{
            if($request->get('isGlobal'))
            {
                event(new GlobalAdviceNotifier($advice, 'created'));
            }

	    	if( $this->saveUserAdvice( $advice, $request->get('priority') ) )
	    	{
	    		return Auth::user()->myAdvice()->where('advices.id',$advice->id)->first();
	    	}
	    }

	    return $this->failedResonse();
    }

    public function failedResonse($data = [], $message = null)
    {
    	$message = is_null($message) ? 'The server is unable to handle this request' : $message;
    	return Res::fail($data, $message);
    }

    private function saveUserAdvice($advice, $priority)
    {
		$userAdvice = new UserAdvice;
    	$userAdvice->user_id = Auth::user()->id;
    	$userAdvice->advice_id = $advice->id;
    	$userAdvice->priority = $priority;
    	return $userAdvice->save();
    }

    public function myAdvice()
    {
    	return Auth::user()->myAdvice()->orderBy('advices.created_at','desc')->get();
    }

    public function globalAdvice()
    {
    	$myGlobalAdvice = Auth::user()->myAdvice(1)->lists('advices.id');
        
        return Advice::globals()->whereNotIn('id', $myGlobalAdvice->toArray())->orderBy('created_at','desc')->get();
    }

    public function addToMyAdvice(AddGlobalAdvice $request, $advice)
    {	
    	if( $this->saveUserAdvice( $advice, $request->get('priority') ) )
    	{
    		return Res::success([]);
    	}
    	return $this->failedResonse();
    }

    public function deleteAccount(){

    }

    public function changeUserSettings(ChangeInfoRequest $request){
        $user = Auth::user();
        $key = $request->route('settingKey');
        $user->fill( [ $key => $request->get($key) ] );

        if( $user->save() )
        {
            return Res::success([],'Your '.$key.' setting is saved successfully');
        }

        return $this->failedResonse();
    }


    public function loginAfterReset(LoginAfterResetRequest $request)
    {
        $email = decrypt( $request->get('data') );
        
        $time = decrypt( $request->get('hash') );

        $currentTime = time();

        if( is_integer( $time ) )
        {
            $validTime = $time * 60;

            if( $currentTime < $validTime )
            {
                $user = User::where('email' , $email)->first();

                $token = $this->jwt->fromUser($user, ['user_id'=>$user->id,'name'=>$user->name, 'email'=>$user->email ] );

                return is_null( $user ) ? Res::fail([], 'You are not the real person') : Res::success( ['token' => $this->jwt->fromUser($user)], $user->name . ', your password is reset. Have a nice day'); 
            }
        }
    }

    public function myAdviceByPrority(){
        $data = Auth::user()->myAdviceByPrority();
        if(count($data) == 0){
            return ['empty' => true];
        }
        return $data;
    }

    public function updatePending( $advice )
    {   
        $userAdvice = UserAdvice::where('user_id', Auth::user()->id )->where('advice_id',$advice->id )->first();
        if( $userAdvice)
        {
            $userAdvice->is_pending = 0;
            $userAdvice->save();
            return Res::success([]);
        }
        return Res::fail([]);
    }
}
