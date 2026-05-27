<?php

namespace App\Models;

use App\Classes\Traits\HasCategory;
use App\Classes\Traits\HasComment;
use App\Classes\Traits\HasTag;
use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory, HasComment, HasTag, HasCategory, PersianDate;

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array'
    ];

    protected $appends = ['image_path'];

    public const FILE_PATH = 'files/article/';
    const MAX_IMAGE_SIZE = 2024;
    private const DEFAULT_IMAGE = 'default.png';

    # region Methods
    public function getAdminRoute(): string
    {
        return route('admin.articles.index', ['id' => $this->id]);
    }

    protected function getCountCommentColumn(): ?string
    {
        return 'count_comments';
    }

    public function getImagePath(): string
    {
        return self::FILE_PATH.$this->id.'/';
    }

    public static function getDescriptionPath(): string
    {
        return self::FILE_PATH.'/description/';
    }

    public static function DefaultImage(): string
    {
        return asset(self::FILE_PATH.self::DEFAULT_IMAGE);
    }

    public function deleteImage(): bool
    {
        if ($this->image){
            Storage::delete($this->getImagePath().$this->image);
        }

        return true;
    }

    public function updateImage(UploadedFile $file): string
    {
        $this->deleteImage();

        $image = basename($file->store($this->getImagePath()));

        $this->update(['image' => $image]);
        return $image;
    }
    # endregion

    # region Scope
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('search')){
            $query->where('title', 'LIKE', '%'.request()->get('search').'%');
        }
        if (request()->filled('author')){
            $query->where('author_id', request()->get('author'));
        }
        if (request()->filled('category')){
            $query->whereHas('categories', function (Builder $builder){

                $builder->where('id', request()->get('category'));
            });
        }
        if (request()->filled('tag')){
            $query->whereHas('tags', function (Builder $builder){

                $builder->where('id', request()->get('tag'));
            });
        }

        return $query;
    }
    # endregion

    # region Accessories
    public function getLinkAttribute(): string
    {
        return route('main.articles.show', [$this->id, $this->slug]);
    }

    public function getImagePathAttribute(): string
    {
        return asset($this->getImagePath().$this->image);
    }
    # endregion

    # region Relations
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    # endregion
}
