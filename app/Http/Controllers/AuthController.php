<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
//use Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string|',
            'password' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->apiResponse(null,'invalid username or password',401);
        }else if(auth()->user()->is_approved == 0){
            return $this->apiResponse(null,'wait for admin confirmation',401);
        }else {
            $user = Auth::user();
            return $this->apiResponse($user,'success',200, ['token' => $token,'type' => 'bearer']);
        }
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
        

    }

    public function register(Request $request){
      //  dd($request);
      try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'blood_type' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'gender' => 'required|between:0,1',
        ]);

       
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
     
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'blood_type' => $request->blood_type,
            'gender' => $request->gender,
            'is_approved' => 0,
            'is_admin' => 0,
          
        ]);

        $token = Auth::login($user);
            //  return $this->apiResponse($user,'success',201, ['token' => $token,'type' => 'bearer']);
            //   dd($token);

            return $this->apiResponse(null,'User created successfully',201,[
                'token' => $token,
                'type' => 'bearer',
            ]);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return $this->apiResponse(null,'An error occurred',502,null);
        }
    }

    public function logout()
    {
        try {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
    }

    public function me()
    {
        try {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
    }

    public function refresh()
    {
        try {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
    }

    public function userApprove($id)
    {
        try {
        if(Auth::user()->is_admin != 1 || Auth::user()->is_approved != 1){
            return $this->apiResponse(null,'you are not authrized for this action',401);
        }else {
            $user=User::find($id);  
           // dd($user);
            $user->is_approved = 1;
            $user->save();
            return $this->apiResponse($user,'user approved',200,null);
        }  
    } catch (\Exception $e) {
        Log::error('An error occurred: ' . $e->getMessage());
        return $this->apiResponse(null,'An error occurred',502,null);
    }
    }

    public function updateMe(Request $request)
    {
      
        try {
            $user = User::findorFail(Auth::user()->id);
            $user->update($request->all());
            return $this->apiResponse($user,'user updated',200,null);
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage());
            return $this->apiResponse(null,'An error occurred',502,null);
        }

    }
}
