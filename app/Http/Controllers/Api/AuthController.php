<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormatter;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $params = $request->all();

            $validator = Validator::make($params, [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least :min characters',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $user = User::where('email', $params['email'])->first();
            if (!$user) {
                return ApiFormatter::createJson(404, 'Account not found', null);
            }

            if (!Hash::check($params['password'], $user->password)) {
                return ApiFormatter::createJson(401, 'Password does not match', null);
            }

            $token = JWTAuth::fromUser($user);
            if (!$token) {
                return ApiFormatter::createJson(500, 'Failed to generate token', null);
            }

            $currentDateTime = Carbon::now();
            $expirationDateTime = $currentDateTime->addSeconds(JWTAuth::factory()->getTTL() * 60);

            $info = [
                'type' => 'Bearer',
                'token' => $token,
                'expires' => $expirationDateTime->format('Y-m-d H:i:s')
            ];

            return ApiFormatter::createJson(200, 'Login successful', $info);

        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token);

            $expiration = $payload->get('exp');
            $expiration_time = date('Y-m-d H:i:s', $expiration);

            $data['name'] = $user['name'];
            $data['email'] = $user['email'];
            $data['exp'] = $expiration_time;

            return ApiFormatter::createJson(200, 'Logged in User', $data);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function refresh()
    {
        try {
            $currentDateTime = Carbon::now();
            $expirationDateTime = $currentDateTime->addSeconds(JWTAuth::factory()->getTTL() * 60);

            $info = [
                'type' => 'Bearer',
                'token' => JWTAuth::refresh(),
                'expires' => $expirationDateTime->format('Y-m-d H:i:s')
            ];

            return ApiFormatter::createJson(200, 'Successfully refreshed', $info);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            JWTAuth::logout();
            return ApiFormatter::createJson(200, 'Successfully logged out', null);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }
}
