<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Home;

class Province extends Model
{
    # region variables
    protected $guarded = [];

    public $timestamps = false;
    # endregion

    #get all province
    public function scopeHasHomes($query)
    {
        return $query->withCount('homes')   
                    ->having('homes_count', '>', 0); //all province with at least 1 home
    }

    # region relations
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function homes()
    {
        return $this->hasMany(Home::class)->active();
    }
    # endregion

    # region constants
    const CACHE_KEY = 'provinces';
    # endregion

    # region methods
    public static function getFromCache()
    {
        return cache()->rememberForever(self::CACHE_KEY, function () {
            return self::query()->with(['cities' => function ($query){
                $query->withCount('homes')->orderByDesc('homes_count');
            }])->withCount(['homes'])->orderByDesc('homes_count')->get();
        });
    }

    public static function findNearestByCoordinates(float $latitude, float $longitude): ?self
    {
        return self::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function (self $province) use ($latitude, $longitude) {
                $province->distance_km = Home::calculateDistance(
                    $latitude,
                    $longitude,
                    (float) $province->latitude,
                    (float) $province->longitude
                );

                return $province;
            })
            ->sortBy('distance_km')
            ->first();
    }

    public function mapViewConfig(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'zoom' => 8,
        ];
    }
    # endregion
}
