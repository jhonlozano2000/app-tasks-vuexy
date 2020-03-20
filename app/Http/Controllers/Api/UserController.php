<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register()
    {

        $validator = Validator::make(request()->input(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
//
        request()->merge(['password' => bcrypt(request('password'))]);
        $user = User::create(request()->input());
        $success['token'] = $user
            ->createToken('tasks api')
            ->accessToken;


        return response()->json($success);
    }


    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('Logged out successfully', 200);
    }



    public function login()
    {
        if (auth()->attempt(request()->input())) {
            $user = auth()->user();
            $success['token'] = $user
                ->createToken('Passport Api')
                ->accessToken;
            return response()->json($success, 200);

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }







}
