<?php

namespace App\Classes\Traits;

use App\Models\Favorite;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavorite(int $user_id = null): bool
    {
        if (!$user_id && auth()->check()){
            $user_id = auth()->id();
        }
        if (!$user_id){
            return false;
        }

        return $this->favorites()->where('user_id', $user_id)->exists();
    }

    public function addToFavorite(int $user_id = null): Favorite
    {
        if (!$user_id && auth()->check()){
            $user_id = auth()->id();
        }

        if (!$user_id) {
            throw new \Illuminate\Auth\AuthenticationException();
        }

        return $this->favorites()->firstOrCreate(['user_id' => $user_id]);
    }

    public function removeFromFavorite(int $user_id = null): bool
    {
        if (!$user_id && auth()->check()){
            $user_id = auth()->id();
        }

        if (!$user_id) {
            throw new \Illuminate\Auth\AuthenticationException();
        }

        return $this->favorites()->where('user_id', $user_id)->delete();
    }
}
