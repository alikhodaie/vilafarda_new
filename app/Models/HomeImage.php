<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    # region Methods
    public function deleteImage(Home $home = null): bool
    {
        if (! $this->name) {
            return true;
        }

        $homeModel = $home ?: $this->home;
        $filename = $this->name;
        $relative = $homeModel->getImagePath().$filename;
        $homeId = (int) $homeModel->id;

        DB::afterCommit(function () use ($relative, $filename, $homeId) {
            $row = Home::query()->whereKey($homeId)->first(['cover', 'video']);
            if ($row && in_array($filename, [$row->cover, $row->video], true)) {
                return;
            }

            Storage::disk('public-folder')->delete($relative);
        });

        return true;
    }

    public function updateImage(UploadedFile $file): string
    {
        $oldName = $this->name;
        $storedName = basename($file->store($this->home->getImagePath(), 'public-folder'));

        $this->update([
            'original_name' => $file->getClientOriginalName(),
            'name' => $storedName,
            'size' => (int) $file->getSize(),
            'type' => $file->getMimeType(),
        ]);

        if ($oldName && $oldName !== $storedName) {
            $relative = $this->home->getImagePath().$oldName;
            DB::afterCommit(function () use ($relative) {
                Storage::disk('public-folder')->delete($relative);
            });
        }

        return $storedName;
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
