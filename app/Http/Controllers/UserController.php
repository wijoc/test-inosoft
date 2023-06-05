<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class UserController extends Controller
{
    protected $userModel;

    public function __construct () {
        $this->userModel = new User();
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'username' => 'required|unique:App\Models\User,username',
                'password' => 'required|confirmed|min:8|max:16',
            ], [
                'username.required' => 'Username is required',
                'username.unique' => 'Username is already registered',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 character',
                'password.max' => 'Password cannot be more than 16 characters'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            $input = [
                'username' => $validator->validated()['username'],
                'password' => Hash::make($validator->validated()['password']),
                'created_at' => Carbon::now()->timezone('UTC')->toIso8601String()
            ];

            $inputUser = $this->userModel->insertUser($input);

            if ($inputUser) {
                return response()->json([
                    'success' => true,
                    'message' => 'Success add new data'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed add new data'
                ], 500);
            }
        }
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(),
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'Username is required',
                'password.required' => 'Password is required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The given data was invalid',
                'errors' => $validator->errors()
            ], 400);
        } else {
            $credentials = $request->only(['username', 'password']);

            if (! $jwtoken = auth()->guard('api')->claims(['type' => 'access_token'])->setTTL(60 * 15)->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'credentials' => "Username / Password is incorect"
                    ]
                ], 400);
            }

            $refreshToken = auth()->guard('api')->claims(['type' => 'refresh_token'])->setTTL(36000)->attempt($credentials);
            return response()->json([
                'success' => true,
                'message' => 'Login success',
                'access_token' => $jwtoken,
                'refresh_token' => $refreshToken
            ], 200)->withCookie(cookie('x-refresh-token', $refreshToken, 36000, null, null, false, true));
        }
    }

    public function refreshToken (Request $request) {
        try {
            $payload = $request->cookie('x-refresh-token') ?? auth()->guard('api')->payload();

            if ($payload->get('type') === 'refresh_token') {
                // auth()->invalidate(); // Invalidate refresh token

                return response()->json([
                    'success' => false,
                    'error' => true,
                    'message' => 'The given data was invalid',
                    'access_token' => auth()->guard('api')->claims(['type' => 'access_token'])->refresh(),
                    'refresh_token' => auth()->guard('api')->claims(['type' => 'refresh_token'])->setTTL(36000)->refresh()
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Refresh Token is Invalid'
                ], 401);
            }
        }
        catch (JWTException $error) {
            if ($error instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'success' => false,
                    'message' => 'Refresh Token is Invalid'
                ], 401);
            }else if ($error instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'success' => false,
                    'message' => 'Refresh Token is Expired'
                ], 401);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Authorization Token not found'
                ], 401);
            }
        }
    }

    public function logout () {
        Cookie::queue(Cookie::forget('x-refresh-token'));
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'success' => true,
            'message' => 'Logout'
        ], 200);
    }
}
