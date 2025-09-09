<?php
namespace App\Services\User;

use App\Jobs\SendVerificationEmailJob;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService{
    public function updateProfile(array $data){
        try{
            $message = "Done Successfully!";
            $user = JWTAuth::parseToken()->authenticate();

            if( isset($data['email']) && $user->email !== $data['email']){
                $user->email_verified_at = null ;
                $user->sendEmailVerificationNotification();
                $message = 'Please check your email to verify your account.' ;
            }
            $user->update($data);
            return ['status'=>'success','data'=>$user , 'message'=>$message];
        }catch(Exception $e){
            return ['status'=>'failed','errors'=>$e->getMessage()];
        }
    }
    /**
     * Summary of completeProfile
     * @param mixed $data
     * @param \App\Models\User $user
     * @return array{message: string}
     */
    public function completeProfile(array $data){
        $user = JWTAuth::parseToken()->authenticate();
        $user->update($data);
        return [
            'message'=>'profile completed successfully'
        ];
    }
}
