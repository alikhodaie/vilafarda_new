<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use App\Support\HomeReviewCriteria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory, PersianDate;

    protected $guarded = [];

    protected $casts = [
        'rating_details' => 'array',
    ];

    const CONFIRMED = 'confirmed';
    const PENDING = 'pending';
    const REJECTED = 'rejected';
    const STATUES = [
        self::PENDING => [
            'value' => self::PENDING,
            'en_text' => 'Pending',
            'fa_text' => 'در انتظار تایید',
            'color' => 'warning'
        ],
        self::CONFIRMED => [
            'value' => self::CONFIRMED,
            'en_text' => 'Confirmed',
            'fa_text' => 'تایید شده',
            'color' => 'success'
        ],
        self::REJECTED => [
            'value' => self::REJECTED,
            'en_text' => 'Rejected',
            'fa_text' => 'رد شده',
            'color' => 'danger'
        ],
    ];

    # region Methods
    public function updateCounter()
    {
        $this->commentable->updateCountComment();
        $this->commentable->updateScore();
    }

    public function ratingScores(): array
    {
        if (! is_array($this->rating_details) || $this->rating_details === []) {
            return [];
        }

        return array_filter(
            $this->rating_details,
            fn ($value) => is_numeric($value) && (int) $value >= 1 && (int) $value <= 5
        );
    }

    public function orderedRatingScores(): array
    {
        $scores = $this->ratingScores();
        $ordered = [];

        foreach (HomeReviewCriteria::KEYS as $key) {
            if (array_key_exists($key, $scores)) {
                $ordered[$key] = (int) $scores[$key];
            }
        }

        foreach ($scores as $key => $value) {
            if (! array_key_exists($key, $ordered)) {
                $ordered[$key] = (int) $value;
            }
        }

        return $ordered;
    }

    public function hasDetailedRatings(): bool
    {
        return $this->ratingScores() !== [];
    }

    public function averageRatingScore(): int
    {
        $scores = $this->ratingScores();

        if ($scores !== []) {
            return HomeReviewCriteria::average($scores);
        }

        return (int) $this->score;
    }

    public function averageRatingScoreLabel(): string
    {
        $scores = $this->ratingScores();

        if ($scores !== []) {
            return persianNumber(HomeReviewCriteria::averageFloat($scores), 1);
        }

        return persianNumber((int) $this->score);
    }

    /**
     * Return comment's status in specific index
     *
     * @param string $index
     * @return string
     */
    public function status($index = 'fa_text'): string
    {
        return self::STATUES[$this->status][$index];
    }
    # endregion

    # region Scope
    /**
     * Active Query
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('email')){
            $query->where('email', request()->get('email'));
        }
        if (request()->filled('comment')){
            $query->where('comment', 'LIKE', '%'.request()->get('comment').'%');
        }
        if (request()->filled('status')){
            $query->where('status', request()->get('status'));
        }

        return $query;
    }
    # endregion

    # region Accessories
    public function getUserNameAttribute(): string
    {
        return ($this->user)
            ? $this->user->full_name
            : '-';
    }

    public function getLimitCommentAttribute(): string
    {
        return Str::limit($this->comment, 30);
    }
    # endregion

    # region Relations
    /**
     * Return morphTo Relationship
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->active();
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Payload for homepage "latest comments" API / mobile swiper.
     */
    public function toIndexApiArray(): array
    {
        $user = $this->user;
        $commentable = $this->commentable;

        $coverPath = asset('assets/images/placeholder.jpg');

        if ($commentable instanceof Home) {
            $coverPath = $commentable->cover_path ?: $coverPath;
        } elseif ($commentable instanceof Article) {
            $coverPath = $commentable->image_path ?: $coverPath;
        }

        $displayName = trim((string) $this->full_name);

        if ($displayName === '' && $user) {
            $displayName = $user->full_name;
        }

        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'score' => (int) $this->score,
            'full_name' => $displayName !== '' ? $displayName : 'کاربر نامشخص',
            'user' => [
                'avatar_path' => $user?->avatar_path ?? User::getDefaultAvatar(),
            ],
            'commentable' => [
                'cover_path' => $coverPath,
            ],
        ];
    }
    # endregion
}
