<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Notifications\UserLogin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|regex:/.+@.+\..+/',
        ],
        [
            'email.required' => 'E-mail address is required.',
            'email.regex' => 'E-mail address is not in correct format.'
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

            $credentials = $request->only('email', 'password');
            if(Auth::once($credentials)) {
                $U = Auth::getUser();
                $IP = $request->getIp();
                (new SlackAgent())->notify(new UserLogin($U->name, $IP));
            }

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

    public function logout()
    {
        $U = auth()->user();

        $U->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('success', 200);
    }
}