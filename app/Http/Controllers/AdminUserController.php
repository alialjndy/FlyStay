<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\FilterAdminUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ManageCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminUserController extends Controller
{
    use ManageCache ;
    /**
     * Retrive all users with roles relationship and store result in cache.
     * @param \App\Http\Requests\User\FilterAdminUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(FilterAdminUserRequest $request){
        $role_name = data_get($request->validated() , 'role_name' , 'all');
        $cacheKey = 'all_users_'.$role_name;

        $allUsers = $this->getFromCache('users' , $cacheKey)
            ?? $this->addToCache(
                'users',
                'all_users_'.$request->role_name,
                User::filterRole($request->role_name ?? null )->with(['roles:id,name','permissions:id,name','favorites'])->paginate(10),
                600);
        return self::paginated($allUsers, UserResource::class);
    }
    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(){
        return self::error('unSupported Yet!','error',501);
    }
    /**
     * Retrive user information with role relationship and store result in cache.
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(User $user){
        $this->authorize('view', $user);

        $cacheKey = 'user_'.$user->id ;
        $user = $this->getFromCache('users',$cacheKey) ??
            $this->addToCache(
                'users',
                $cacheKey,
                $user->load(['roles','permissions','favorites']),
                600);
        return self::success([new UserResource($user)]);
    }
    /**
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(){
        return self::error('unSupported Yet!','error',501);
    }
    /**
     * Delete user from DB.
     * Delete cache associated with users.
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(User $user){
        $this->authorize('delete', $user);
        $user->delete();
        $this->clearCache('users');
        return self::success([null]);
    }
}
