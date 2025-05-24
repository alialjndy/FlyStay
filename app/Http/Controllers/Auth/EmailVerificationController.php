<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    protected $authService ;
    public function __construct(AuthService $authService){
        $this->authService = $authService ;
    }
    /**
     * Display a message prompting the user to verify their email.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function notice(Request $request){
        return response()->json([
            'message' => 'Please verify your email before continuing.'
        ]);
    }
    /**
     * Handle the email verification link callback.
     * @param \Illuminate\Foundation\Auth\EmailVerificationRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return response()->json([
            'message' => 'Email verified successfully.'
        ]);
    }
    /**
     * Resend the email verification link.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resend(Request $request){
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'message' => 'Verification link sent!'
        ]);
    }
    public function loginWithEmail(string $accessToken){
        $result = $this->authService->loginWithGoogle($accessToken);
        return response()->json([
            'message'=>$result['message'],
            'token'=>$result['token'] ? $result['token'] : null
        ],$result['code']);
    }
}
