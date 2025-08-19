<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PhoneNumberRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService ;
    public function __construct(AuthService $authService){
        $this->authService = $authService ;
    }
    /**
     * Summary of register
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request){
        $data = $this->authService->register($request->validated());
        return $this->success(['token'=>$data['token']] , 201,$data['message']);
    }
    /**
     * Summary of login
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
        $data = $this->authService->login($request->validated());
        return $data['status'] == 'success' ? self::success(['token'=>$data['token'] ,'roles'=>$data['roles']]) :
        self::error('Error Occurred','error',400,[$data['message']]);
    }
    /**
     * Summary of me
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function me(){
        $user = $this->authService->me()->load(['roles:id,name','permissions:id,name','favorites']);
        return $this->success([new UserResource($user)]);
    }
    /**
     * Summary of logout
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function logout(){
        $data = $this->authService->logout();
        return $this->success([] , 200 , $data['message'],$data['status']);
    }
    /**
     * Summary of completeProfile
     * @param \App\Http\Requests\Auth\PhoneNumberRequest $phoneRequest
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function completeProfile(PhoneNumberRequest $phoneRequest){
        $result = $this->authService->completeProfile($phoneRequest->validated());
        return $this->success([],200,$result['message']);
    }
}
