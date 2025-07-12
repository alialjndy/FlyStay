<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use AuthorizesRequests ;
    /**
     * Summary of success
     * @param array $data
     * @param mixed $code
     * @param mixed $message
     * @param mixed $status
     * @return JsonResponse|mixed
     */
    protected function success(array $data = [],$code = 200,$message = 'Done Successfully!',$status = 'success'){
        return response()->json([
            'status'=>$status,
            'message'=>$message,
            'data'=>$data
        ],$code);
    }
    /**
     * Summary of error
     * @param mixed $message
     * @param mixed $status
     * @param mixed $code
     * @param array $errors
     * @return JsonResponse|mixed
     */
    protected function error($message = 'Error Occurred',$status='error',$code = 400,array $errors = []){
        return response()->json([
            'status'=>$status ,
            'message'=>$message,
            'errors'=>$errors ?: null ,
        ],$code);
    }
    /**
     * Return a paginated JSON response
     *
     * @param LengthAwarePaginator $paginator
     * @param array $data
     * @return JsonResponse
     */
    public static function paginated($paginator,$resourceClass = null){
        $transformedItemData = is_null($resourceClass)
        ? $paginator->items()
        : $resourceClass::collection($paginator->items());
        return response()->json([
            'status'=>'success',
            'data'=>$transformedItemData,
            'pagination'=>[
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
            ]
        ]);
    }
}
