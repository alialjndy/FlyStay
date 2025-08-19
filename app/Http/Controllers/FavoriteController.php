<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\Favorite\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $favoriteService ;
    public function __construct(FavoriteService $favoriteService){
        $this->favoriteService = $favoriteService ;
    }
    public function handle($type , $id){
        $info = $this->favoriteService->toogleFavorite($type , $id);
        return $info['status'] === 'success' ? self::success([null] , $info['code'] , $info['message']) :
        self::error($info['message'] , 'error' ,$info['code'] ,[null]);
    }
}
