<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function success(array $data = [],$code = 200,$message = 'Done Successfully!',$status = 'success'){
        return response()->json([
            'status'=>$status,
            'message'=>$message,
            'data'=>$data
        ],$code);
    }
    protected function error($message = 'Error Occurred',$status='error',$code = 400,array $errors = []){
        return response()->json([
            'status'=>$status ,
            'message'=>$message,
            'errors'=>$errors ?: null ,
        ],$code);
    }
}
