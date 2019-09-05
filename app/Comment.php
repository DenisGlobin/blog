<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\Date\BlogDateFormat;

class Comment extends Model
{
    use BlogDateFormat;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'user_id', 'comment_id',
    ];

    /**
     * Retrive user related with the comment.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrive article related with the comment.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
