<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetLinkRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    protected $authService ;
    public function __construct(AuthService $authService){
        $this->authService = $authService ;
    }
    /**
     * Summary of sendResetLink
     * @param \App\Http\Requests\Auth\SendResetLinkRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendResetLink(SendResetLinkRequest $request){
        $result = $this->authService->sendResetLink($request->validated());
        return response()->json([
            'message'=>$result['message']
        ],$result['code']);
    }
    /**
     * Summary of resetPassword
     * @param \App\Http\Requests\Auth\ResetPasswordRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request){
        $result = $this->authService->resetPassword($request->validated());
        return response()->json([
            'message'=>$result['message']
        ],$result['code']);
    }
    public function changePassword(ChangePasswordRequest $request){
        $info = $this->authService->changePassword($request->validated());
        return $info['status'] === 'success' ?
            self::success([],$info['code'],$info['message']) :
            self::error('Error Occurred',$info['status'],$info['code'],[$info['error']]);
    }
}
