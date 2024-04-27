<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    //
    //Register
    public function Register(RegisterRequest $request)
    {
         $request->validated();
         try{
            DB::beginTransaction();
            $user = User::create([
                'name' =>  $request->name,
                 'email' => $request->email,
                  'password' => Hash::make($request->password),
            ]);

            $token = Auth::login($user);
            DB::commit();
            return response()->json([
            'status'=>'تم تسجيل حساب',
            'user' =>$user,
            'token'=>$token,
            ]);
         }
         catch(Throwable $th){
            Log::debug($th);
            $e=\Log::error( $th->getMessage());
            return response()->json([
                'status' =>'error',

              ]);
         }
    }

    //Login
     public function login(LoginRequest $request){
         try{

            $request->validated();
            $credentials = $request->only('email','password');
            $token = Auth::attempt($credentials);
              if(!$token){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
              };
              $user = Auth::user();
              return response()->json([
                'status'=>' تم تسجيل الدخول  ',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',

]              ]);
         }
         catch(\Throwable $th){
            Log::debug($th);
            $e=\Log::error( $th->getMessage());
            return response()->json([
                'status' =>'error',

              ]);
         }
     }

     //logout
      public function logout(){
        Auth::logout();
        return response()->json([
           'status'=>'تم تسجيل الخروج'
        ]);
      }
}

