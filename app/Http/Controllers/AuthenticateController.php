<?php

namespace App\Http\Controllers;
use App\User;
use App\Models\Validation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthenticateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    #This function validates user credentials.
    public function Authenticate(Request $request)
    {
        #Instantiate Validation Class in Model
        $v_validation = new Validation;

        #Validate Input
        if($v_validation->validateAuthentication($request->only('email', 'password')) == true){

            $credentials = $request->only('email', 'password');

            try {
                // verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['status'=>'success','data'=>'invalid_credentials'],200);
                }
            } catch (JWTException $e) {
                // something went wrong
                return response()->json(['status'=>'success','data'=>'could_not_create_token'],200);
            }

            // if no errors are encountered we can return a JWT
            return response()->json(compact('token'), 200); 

        }else{
            #Validation Failed
            return response()->json(['status'=>'success','data'=>'validation_failed'],200);
        }

    }


    #This function gets the authorized user from the JWT Token
    public function getAuthenticatedUser()
    {
    try {

        #User not found
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

        #Catch token expiry 
    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

        return response()->json(['token_expired'], $e->getStatusCode());

        #Catch invalid token
    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

        return response()->json(['token_invalid'], $e->getStatusCode());

         #Catch invalid token
    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

        return response()->json(['token_absent'], $e->getStatusCode());

    }

        // The token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }
}
