<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\Date\BlogDateFormat;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use BlogDateFormat;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'message', 'user_id',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Retrive user related with the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrive comments related with the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
