<?php

namespace App\Models;

use App\Services\HomePhotoWebpEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HomeImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    # region Methods
    public function deleteImage(Home $home = null): bool
    {
        if ($this->name){
            $path = ($home) ? $home->getImagePath(): $this->home->getImagePath();
            Storage::delete($path.$this->name);
        }

        return true;
    }

    public function updateImage(UploadedFile $file): string
    {
        $this->deleteImage();

        $originalName = $file->getClientOriginalName();
        $stored = app(HomePhotoWebpEncoder::class)->storeOptimizedWebp($file, trim($this->home->getImagePath(), '/'), [
            'max_edge' => Home::IMAGE_GALLERY_MAX_LONG_EDGE,
            'quality' => Home::IMAGE_GALLERY_WEBP_QUALITY,
        ]);

        $this->update([
            'original_name' => $originalName,
            'name' => $stored['name'],
            'size' => $stored['size'],
            'type' => $stored['type'],
        ]);

        return $stored['name'];
    }
    # endregion

    # region Accessories
    public function getImagePathAttribute(): string
    {
        if (!$this->name) {
            return asset('assets/images/placeholder.jpg');
        }
        
        // اگر name یک URL کامل است، مستقیماً برگردان
        if (filter_var($this->name, FILTER_VALIDATE_URL)) {
            return $this->name;
        }
        
        // در غیر این صورت از مسیر محلی استفاده کن
        return asset($this->home->getImagePath().$this->name);
    }
    # endregion

    # region Relations
    public function home()
    {
        return $this->belongsTo(Home::class);
    }
    # endregion
}
