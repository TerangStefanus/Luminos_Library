<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // tambahkan ini
use App\Models\User; // import model user
use Illuminate\Validation\Rule;
use Validator; // import library untuk validasi
use Mail;
use App\Mail\UserMail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'fullname' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
            'phonenumber' => 'required',
            'address' => 'required'
        ]); // membuat rule validasi input

        $registrationData['role'] = 'string';
        $registrationData['token'] = rand(100000,999999);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error validasi input

        $registrationData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($registrationData); // membuat user baru

        try{
            $detail = [
                'body' => $registrationData['token']
            ];
            Mail::to($registrationData['email'])->send(new UserMail($detail));
            return response([
                'message' => 'Register Success',
                'user' => $user
            ], 200); // return data user dalam bentuk json
        }catch (Exception $e){
            return;
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input
        
        if (!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401); // return error gagal login

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken; // generate Token

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
            'password' => $request->password
        ]); // return data user dan token dalam bentuk json
    }

    public function verify(Request $request){
        $verifyData = $request->all();
        $validate =  Validator::make($verifyData, [
            'token' => 'required|digits:6|numeric'
        ]);

        
        $user = DB::table('user')->where('token', $verifyData['token']);
        // User::where('token', $verifyData['token'])->get();
        if (!is_null($user)) {
            User::where('token', $verifyData['token'])
                    ->update(['token' => '1']);
            return response([
                'message' => "Verification successful",
                'data' => $user
            ], 200);
        } 

        return response([
            'message' => 'User not found',
            'data' => null
        ], 404);
    }
}
