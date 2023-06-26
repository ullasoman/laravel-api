<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $token = $authUser->createToken('token')->plainTextToken;
            $success['token'] =  $token;
            
            $success['name'] =  $authUser->name;

            $cookie = cookie('jwt', $token, 60 * 24); // 1 day
            // return $this->sendResponse([
            //     'message' => $token
            // ])->withCookie($cookie);

            return $this->sendResponse($success, 'User signed in')->withCookie($cookie);
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User created successfully.');
    }
    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();
        $cookie = Cookie::forget('jwt');

        // return response([
        //     'message' => 'You have successfully logged out and the token was successfully deleted'
        // ])->withCookie($cookie);

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
