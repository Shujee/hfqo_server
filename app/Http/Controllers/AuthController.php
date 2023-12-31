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
use App\Http\Controllers\RequestIP;
use App\Notifications\GenericException;

class AuthController extends Controller
{
    use RequestIP;

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
        $IP = "<Unknown>";
        $U = null;

        try
        {
            //get token first
            $login_endpoint = config('services.passport.login_endpoint');
            $login_params = [
                'grant_type' => 'password',
                'client_id' => (int)config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
            ];

            $response = $http->post($login_endpoint, [ 'form_params' => $login_params ]);

            $res = $response->getBody()->getContents();
            $Token = json_decode($res, true);

            $credentials = $request->only('email', 'password');
            $U = null;
            if(Auth::once($credentials)) {
                $U = Auth::getUser();
                $IP = $this->getIp();

                (new SlackAgent())->notify(new UserLogin($U->name, $IP));
            }

            return [
                'name' => $U->name,
                'type' => $U->type,
                'token' => $Token,
            ];
        }
        catch(Exception $e)
        {
            (new SlackAgent())->notify(new GenericException($IP, $U, $e));

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
        $PATCH = 3;

        //$commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        
        //read last commit hash from master branch
        $commitHash = file_get_contents('./HFQ/.git/refs/heads/master'); // or branch x
        
        //delimit short version (first 7 chars) of hash in square brackets
        $commitHash = substr_replace($commitHash, ']', 7, 0);
        $commitHash = substr_replace($commitHash, '[', 0, 0);
        
        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('UTC'));

        return [
            'version' => sprintf('v%s.%s.%s', $MAJOR, $MINOR, $PATCH),
            'commit_hash' => sprintf('%s', $commitHash),
            'commit_date' => $commitDate->format('Y-m-d H:i:s'),
        ];
    }
}