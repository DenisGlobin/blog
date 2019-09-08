<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\Date\BlogDateFormat;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use BlogDateFormat;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'user_id', 'comment_id',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
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
