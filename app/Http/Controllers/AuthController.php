<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Notification;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|regex:'. \Globals::REGEX_MOBILE_NUMBER,
        ],
        [
            'email.required' => 'E-mail address is required.',
            'email.regex' => \Globals::REGEX_MOBILE_NUMBER_MSG
        ]);

        $http = new \GuzzleHttp\Client; 

        try
        {
            //get token first
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => (int)config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                ]
            ]);


            $Token = json_decode($response->getBody()->getContents(), true);

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$Token['access_token']}"
            ];

            return [
                    'token' => $Token,
            ];
        }
        catch(Exception $e)
        {
            if($e->getCode() == 401)
                return response()->json([
                                            'error' => 'Invalid credentials', 
                                        ], 401);
            else
                return response()->json([
                                            'messsage' => $e
                                        ], $e->getCode());
        }
    }

    public function register(Request $request)
    {
        $ValidationResult = null;

        // 1. Must be in 11-digits format: 03XXXXXXXXX.
        // 2. Must NOT already exist in users table.
        $v = Validator::make($request->all(), [
            'email' => 'regex:' . \Globals::REGEX_MOBILE_NUMBER . '|unique:users',
        ],
        [
            'email.regex' => \Globals::REGEX_MOBILE_NUMBER_MSG,
            'email.unique' => 'This email is already registered.',
        ]);

        if ($v->fails())
            $ValidationResult = $v->errors();
        else
            $ValidationResult = true;

        if($ValidationResult === true)
        {
            //$U = User::CreateFromMobileNumber($request->mobile);

            return response()->json([
                'message' => 'success'
            ], 201);
        }
        else
            return response()->json($ValidationResult, 422);
    }

    public function logout()
    {
        $U = auth()->user();

        $U->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('success', 200);
    }
}