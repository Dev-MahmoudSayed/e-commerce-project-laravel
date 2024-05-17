<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\authResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\userDataResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

     public function index()
     {
         return new userDataResource(auth()->user());
     }

     public function register(RegisterRequest $request)
     {
         $request->validated($request->all());
         $user = User::create([
             'name' =>$request->name,
             'email' => $request->email,
             'password' => bcrypt($request['password']),
         ]);

         return new userDataResource($user);

     }

     public function login(LoginRequest $request)
     {
        $user= $request->validated($request->all());
         if(!Auth::attempt($request->only(['email','password'])))
         {
            return response([
                         'message' => 'Bad Credentials',
                     ], 401);
                    }
         $user = User::where('email',$request->email)->first();

         return new userDataResource($user);
        }


     public function logout(Request $request) {

		$request->user()->currentAccessToken()->delete();
		return response()->json([
            'message' => 'logout success',
        ], 200);
	}

    public function deleteUser($id)
    {
        $user = auth()->user;
        $user->delete();
        return response()->json(['data' => $user], 200);
    }

}
