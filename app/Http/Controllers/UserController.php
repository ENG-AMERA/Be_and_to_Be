<?php

namespace App\Http\Controllers;

use App\Models\Fcm;
// use Illuminate\Foundation\Auth\User;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    private $apiKey = 'fkfZK52vQUaJQqhQj5h1ZZ:APA91bHka0OY6_i5TafWlG_h-k1hahb5RHUf4spQnRdBxr0setYZZMG5YEzW-T2pzwd8d96kKihurJqMn8o4YQG8eB-vN7bpJqWRjuqnTtTRhcM-dq9w6dI';
    private $apiUrl = 'https://www.traccar.org/sms/';

    public function sendOtp(string $phone): void
    {
        $otp = rand(10000, 99999);

        Otp::create([
            'phone' => $phone,
            'otp' => $otp,
            'used' => false,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(10),
        ]);

        $message = "كود التحقق الخاص بك هو: $otp. يرجى عدم مشاركته مع أي شخص.";

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->apiUrl, [
            'to' => $phone,
            'message' => $message
        ]);

        Log::info('OTP sent to ' . $phone);
        Log::info('Response: ' . $response->body());
    }



public function register(RegisterRequest $request) {
    $user = User::create([
        'fullname'     => $request->fullname,
        'phonenumber'  => $request->phonenumber,
        'password'     => bcrypt($request->password),
        'role'         => 'client',
      //  'device_token' =>$request->device_token,
    ]);

  //  $this->sendOtp($request->phonenumber);

    return response()->json($user, 201);
}


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['phonenumber', 'password']);
        $deviceToken = request(['device_token']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

           $user = auth()->user();
    $data = [
        'access_token' => $token,
        'token_type' => 'bearer',
      //  'expires_in' => auth()->factory()->getTTL() * 60,
        'user' => $user,
    ];

     Fcm::updateOrCreate(
    ['device_token' => $deviceToken['device_token']],
    ['user_id' => $user->id]
        );

    if ($user->role === 'admin') {
        $admin = $user->admin;
        if ($admin && $admin->branch) {
            $data['branch'] = $admin->branch;
        }
    }

    return response()->json($data);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
