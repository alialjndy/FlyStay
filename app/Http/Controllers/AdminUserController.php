<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\FilterAdminUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * @param \App\Http\Requests\User\FilterAdminUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(FilterAdminUserRequest $request){
        $allUsers = User::filterRole($request->role_name)->with(['roles:id,name','permissions:id,name'])->paginate(10);
        return self::paginated($allUsers, UserResource::class);
    }
    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(){
        return self::error('unSupported Yet!','error',501);
    }
    /**
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user){
        $this->authorize('view', $user);
        $user = $user->load(['roles','permissions']);
        return self::success([new UserResource($user)]);
    }
    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(){
        return self::error('unSupported Yet!','error',501);
    }
    /**
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(User $user){
        $this->authorize('delete', $user);
        $user->delete();
        return self::success([null]);
    }
}
