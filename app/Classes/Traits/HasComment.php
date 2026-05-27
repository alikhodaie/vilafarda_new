<?php


namespace App\Classes\Traits;


use App\Models\Comment;
use Illuminate\Support\Str;

trait HasComment
{
    # region Relations
    /**
     * Return Comments
     *
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function activeComments()
    {
        return $this->comments()->active();
    }
    # endregion

    # region Methods
    public function addComment(
        string $comment,
        string $email,
        string $name,
        int $parent_id = null,
        int $score = 0,
        int $user_id = null,
        ?array $ratingDetails = null
    ): Comment {
        if (!$user_id && auth()->check()){
            $user_id = auth()->id();
        }

        return $this->comments()->create([
            'comment' => $comment,
            'email' => $email,
            'full_name' => $name,
            'score' => $score,
            'rating_details' => $ratingDetails,
            'parent_id' => $parent_id,
            'user_id' => $user_id
        ]);
    }

    protected function getCountCommentColumn(): ?string
    {
        return null;
    }


    protected function getScoreColumn(): ?string
    {
        return null;
    }

    public function updateCountComment(): bool
    {
        if (!$this->getCountCommentColumn()){
            return false;
        }

        $this->update([$this->getCountCommentColumn() => $this->comments()->active()->count()]);
        return true;
    }

    public function updateScore(): bool
    {
        if (!$this->getScoreColumn()){
            return false;
        }

        $this->update([$this->getScoreColumn() => $this->comments()->active()->where('score', '!=', 0)->avg('score') ?? 0]);
        return true;
    }

    public function getCommentType(): string
    {
        $model = self::class;
        $model = explode('\\', $model);
        $model = $model[array_key_last($model)];
        $model = Str::lower($model);

        return __("title.$model");
    }

    public function getAdminRoute(): string
    {
        return '';
    }
    # endregion
}
