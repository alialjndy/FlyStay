<?php
namespace App\Http\Services\Auth;

use App\Jobs\SendAuthenticatedEmailJob;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService{
    /**
     * Summary of register
     * @param array $data
     * @throws \Exception
     * @return array{message: string, token: mixed}
     */
    public function register(array $data){
        try{
            $user = User::create($data);
            SendAuthenticatedEmailJob::dispatch($user->id);
            return [
                'token'=>JWTAuth::fromUser($user),
                'message'=>'User registered. Please check your email to verify your account.'
            ];
        }catch(Exception $e){
            Log::error('Error occurred when register user '.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
    /**
     * Summary of login
     * @param array $data
     * @throws \Exception
     * @return array{code: int, message: string, status: string}
     */
    public function login(array $data){
        try{
            if(!JWTAuth::attempt($data)){
                return [
                    'status' => 'failed',
                    'message' => 'Invalid credentials. Please try again.',
                    'code' => 401
                ];
            }else{
                return [
                    'status' => 'success',
                    // 'token' => JWTAuth::attempt($data),
                    'message' => 'Login successful.',
                    'code' => 200,
                ];
            }
        }catch(Exception $e){
            Log::error('Error occurred when login user '.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
    /**
     * Summary of logout
     * @return array{message: string, status: string}
     */
    public function logout(){
        Auth::logout();
        return [
            'status'=>'success',
            'message'=>'user has been logged out successfully'
        ];
    }
    /**
     * Summary of me
     * @return User
     */
    public function me(){
        return Auth::user();
    }
    /**
     * Summary of handleGoogleCallback
     * @return array{message: string, token: mixed}
     */
    public function handleGoogleCallback(){
        $googleUser = Socialite::driver('google')->stateless()->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();

        $user = User::firstOrCreate(
            ['email'=>$googleUser->getEmail()],
            [
                'name'          =>$googleUser->getName() ?? $googleUser->getNickname(),
                'email'         => $googleUser->getEmail(),
                'password'      => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );
        $token = JWTAuth::fromUser($user);
        return [
            'message' => 'Login successful via Google but you need to complete your proifle informations',
            'token' => $token,
        ];
    }
    /**
     * Summary of loginWithGoogle
     * @param string $accessToken
     * @return array{code: int, message: string, token: mixed|array{code: int, message: string}}
     */
    public function loginWithGoogle(string $accessToken)
    {
        try {
            $tokenInfo = Http::get("https://www.googleapis.com/oauth2/v3/tokeninfo", [
                'access_token' => $accessToken
            ]);

            if (!$tokenInfo->ok()) {
                Log::warning('Invalid Google access token attempt.', ['access_token' => $accessToken]);
                return [
                    'message' => 'Invalid or expired Google access token.',
                    'code' => 401
                ];
            }

            $tokenData = $tokenInfo->json();

            if ($tokenData['aud'] !== config('services.google.client_id')) {
                Log::warning('Token audience mismatch.', ['aud' => $tokenData['aud']]);
                return [
                    'message' => 'Token not issued for this application.',
                    'code' => 403
                ];
            }

            if (isset($tokenData['exp']) && $tokenData['exp'] < time()) {
                return [
                    'message' => 'Token has expired.',
                    'code' => 401
                ];
            }

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->userFromToken($accessToken);

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                Log::info('Google login failed. Email not registered.', ['email' => $googleUser->getEmail()]);
                return [
                    'message' => 'No account associated with this Google email.',
                    'code' => 404
                ];
            }

            $token = JWTAuth::fromUser($user);

            Log::info('Google login successful.', ['user_id' => $user->id, 'email' => $user->email]);

            return [
                'message' => 'Login successful via Google.',
                'token' => $token,
                'code' => 200
            ];

        } catch (Exception $e) {
            Log::error('Google login exception.', ['error' => $e->getMessage()]);
            return [
                'message' => 'An unexpected error occurred.',
                'code' => 500
            ];
        }
    }

    /**
     * Summary of completeProfile
     * @param mixed $data
     * @param \App\Models\User $user
     * @return array{message: string}
     */
    public function completeProfile($data){
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->phone_number = $data['phone_number'];
        $user->save();
        return [
            'message'=>'profile completed successfully'
        ];
    }
    /**
     * Summary of sendResetLink
     * @param mixed $data
     * @return array{code: int, message: string}
     */
    public function sendResetLink($data){
        $status = Password::sendResetLink(['email'=>$data['email']]);
        if($status === Password::RESET_LINK_SENT){
            return [
                'message'=>'A password reset link has been sent to your email address.',
                'code'=>200
            ];
        }else{
            return [
                'message'=>'An error occurred. Please try again later.',
                'code'=>500
            ];
        }
    }
    /**
     * Summary of resetPassword
     * @param mixed $data
     * @return array{code: int, message: string|array{code: int|string, message: string}}
     */
    public function resetPassword($data){
        $status = Password::reset($data,function ($user, $password) {
            $user->forceFill([
            'password' => Hash::make($password)])->save();
        });
        return $status === Password::PASSWORD_RESET ?
                ['message'=>'password updated successfully','code'=>'200'] :
                ['message'=>'error occurred','code'=>500];
    }
}
