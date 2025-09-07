<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\PhoneNumberRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService ;
    public function __construct(UserService $userService){
        $this->userService = $userService ;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return self::error('UnSupported','error',501);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return self::error('UnSupported','error',501);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return self::error('UnSupported','error',501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, User $user)
    {
        $UpdatedUser = $this->userService->updateProfile($request->validated());
        return $UpdatedUser['status'] === 'success' ? self::success([$UpdatedUser['data']], 200 , $UpdatedUser['message']) :
        self::error('Error Occurred','error',400,[$UpdatedUser['errors']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
    public function completeProfile(PhoneNumberRequest $phoneRequest){
        $result = $this->userService->completeProfile($phoneRequest->validated());
        return $this->success([],200,$result['message']);
    }
}
