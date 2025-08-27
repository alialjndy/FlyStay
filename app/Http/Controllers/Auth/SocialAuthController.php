<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected $authService ;
    public function __construct(AuthService $authService){
        $this->authService = $authService ;
    }
    public function redirectToGoogle(){
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return response()->json([
            'status'=>'success',
            'url'=>$url ,
        ],200);
        // return Socialite::driver('google')->stateless()->redirect();

    }
    public function handleGoogleCallback(){
        $cookie = $this->authService->handleGoogleCallback();
        return redirect()->away("http://localhost:5173/auth/callback")->withCookie($cookie);
    }
}
