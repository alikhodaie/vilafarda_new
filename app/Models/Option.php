<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Option extends Model
{
    use HasFactory;

    protected $guarded = [];

    # region Const
    const CACHE_KEY = 'options';
    const MAX_FILE_SIZE = 2024;
    const ICON_PATH = 'files/homes/options/';
    const ICON_TYPE_IMAGE = 'image';
    const ICON_TYPE_FONT = 'font';
    # endregion

    protected $appends = ['icon_path', 'icon_class', 'is_font_icon'];

    # region Accessories
    public function isFontIcon(): bool
    {
        return ($this->icon_type ?? self::ICON_TYPE_IMAGE) === self::ICON_TYPE_FONT;
    }

    public function getIsFontIconAttribute(): bool
    {
        return $this->isFontIcon();
    }

    public function getIconPathAttribute(): ?string
    {
        if ($this->isFontIcon()) {
            return null;
        }

        return asset(self::ICON_PATH.$this->icon);
    }

    public function getIconClassAttribute(): ?string
    {
        if (! $this->isFontIcon()) {
            return null;
        }

        return 'bi '.$this->icon;
    }
    # endregion

    # region Methods
    public static function getFromCache(): Collection
    {
        return cache()->rememberForever(self::CACHE_KEY, function (): Collection {
            return self::all();
        });
    }

    public function deleteIcon(): bool
    {
        if ($this->isFontIcon()) {
            return true;
        }

        Storage::delete(self::ICON_PATH.$this->icon);

        return true;
    }
    # endregion

    # region Scopes
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('title')){
            $query->where('title', 'LIKE', '%'.request()->get('title').'%');
        }

        return $query;
    }
    # endregion

    # region Relations
    public function homes()
    {
        return $this->belongsToMany(Home::class);
    }
    # endregion
}
