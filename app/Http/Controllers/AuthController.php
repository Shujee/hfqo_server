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

Log::info("login_endpoint:" . config('services.passport.login_endpoint'));
Log::info("client_id:" . config('services.passport.client_id'));
Log::info("client_secret:" . config('services.passport.client_secret'));
Log::info("username:" . $request->email);
Log::info("password:" . $request->password);

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

            Log::info("response:" . $response->getBody()->getContents());

            $Token = json_decode($response->getBody()->getContents(), true);

            Log::info("Token:" . $Token);

            $credentials = $request->only('email', 'password');
            $U = null;
            if(Auth::once($credentials)) {
                $U = Auth::getUser();
                $IP = $request->getIp();

                try {
                    (new SlackAgent())->notify(new UserLogin($U->name, $IP));
                }
                catch(Exception $e) {
                    Log::alert("Slack notification failed [USER LOGGED IN]. {$e->getMessage()}. User: {$U->name}");
                }
            }

            return [
                'name' => $U->name,
                'type' => $U->type,
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

    public function gitversion() { 
        $MAJOR = 1;
        $MINOR = 6;
        $PATCH = 2;
    
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('UTC'));

        return [
            'version' => sprintf('v%s.%s.%s', $MAJOR, $MINOR, $PATCH),
            'commit_hash' => sprintf('%s', $commitHash),
            'commit_date' => $commitDate->format('Y-m-d H:i:s'),
        ];
    }
}