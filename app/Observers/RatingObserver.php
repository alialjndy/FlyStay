<?php

namespace App\Observers;

use App\Models\Rating;

class RatingObserver
{
    /**
     * Handle the Rating "created" event.
     */
    public function created(Rating $rating): void
    {
        $this->handle($rating ,'created');
    }

    /**
     * Handle the Rating "updated" event.
     */
    public function updated(Rating $rating): void
    {
        $this->handle($rating ,'updated');
    }

    /**
     * Handle the Rating "deleted" event.
     */
    public function deleted(Rating $rating): void
    {
        $this->handle($rating,'deleted');
    }

    /**
     * Handle the Rating "restored" event.
     */
    public function restored(Rating $rating): void
    {
        //
    }

    /**
     * Handle the Rating "force deleted" event.
     */
    public function forceDeleted(Rating $rating): void
    {
        //
    }
    private function handle(Rating $rating ,string $event){
        $hotel = $rating->hotel ;

        if(!$hotel)return ;
        switch($event) {
            case 'created' :
                $hotel->increment('count_ratings');
                $hotel->increment('sum_ratings', $rating->rating);
                break;
            case 'updated' :
                $hotel->sum_ratings = $hotel->sum_ratings - $rating->getOriginal('rating') + $rating->rating;
                $hotel->save();
                break;
            case 'deleted':
                $hotel->decrement('count_ratings');
                $hotel->decrement('sum_ratings', $rating->rating);
                break;
        }
        $hotel->rating = $hotel->count_ratings > 0 ? (int) round($hotel->sum_ratings / $hotel->count_ratings)  : 0 ;
        $hotel->save();
    }
}
