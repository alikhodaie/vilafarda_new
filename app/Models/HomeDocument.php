<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HomeDocument extends Model
{
    protected $guarded = [];

    protected $appends = [
        'url',
        'is_image',
        'is_pdf',
    ];

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class);
    }

    public function getUrlAttribute(): string
    {
        return asset(Home::DOCUMENT_PATH.$this->home_id.'/'.$this->name);
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->extension(), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'heic', 'heif'], true);
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->extension() === 'pdf';
    }

    public function extension(): string
    {
        return strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
    }

    public function deleteStoredFile(): void
    {
        if (! $this->name) {
            return;
        }

        Storage::disk('public-folder')->delete(Home::DOCUMENT_PATH.$this->home_id.'/'.$this->name);
    }
}
