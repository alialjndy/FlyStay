<?php
namespace App\Services\Auth;

use App\Jobs\SendAuthenticatedEmailJob;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response as FacadesResponse;
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
            $user->sendEmailVerificationNotification();
            $user->assignRole('customer');
            // SendAuthenticatedEmailJob::dispatch($user);
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
                    'token'=>null,
                    'code' => 401
                ];
            }else{
                $token = JWTAuth::attempt($data);
                $user = JWTAuth::user();
                $roles = $user->getRoleNames();
                return [
                    'status' => 'success',
                    'token' => $token,
                    'message' => 'Login successful ',
                    'roles'=>$roles,
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
    public function handleGoogleCallback(){
        $googleUser = Socialite::driver('google')->stateless()->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();

        $user = User::firstOrCreate(
            ['email'=>$googleUser->getEmail()],
            [
                'name'=>$googleUser->getName() ?? $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        $message = $user->wasRecentlyCreated ? 'Welcome! Please complete your profile' : 'Welcome Back!';
        if($user->wasRecentlyCreated){
            $user->assignRole('customer');
        }

        $token = JWTAuth::fromUser($user);

        $cookie = Cookie::make('jwt_token', $token, 60, '/', null, false, true); // HttpOnly=true, Secure=false لـ localhost

        $frontendURL = "http://localhost:5173/auth/callback?status=success&jwt_token=" . $token;
        return redirect()->away($frontendURL)->withCookies([$cookie]);
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
        $status = Password::reset($data,function (User $user, string $password) {
            $user->forceFill([
            'password' => Hash::make($password)])
            ->setRememberToken(Str::random(60));
            $user->save();

            // event(new PasswordReset($user)); // Override this method
        });
        return $status === Password::PASSWORD_RESET ?
                ['message'=>'password updated successfully','code'=>'200'] :
                ['message'=>'error occurred','code'=>500];
    }
}
