<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\BlogDateFormat;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use BlogDateFormat;
    use SoftDeletes;
    use Searchable;

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
     * Retrieve user related with the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve comments related with the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Retrieve tags related with the article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function searchableOptions()
    {
        return [
            // You may wish to change the default name of the column
            // that holds parsed documents
            'column' => 'searchable',
            // You may want to store the index outside of the Model table
            // In that case let the engine know by setting this parameter to true.
            'external' => true,
            // If you don't want scout to maintain the index for you
            // You can turn it off either for a Model or globally
            'maintain_index' => true,
            // Ranking groups that will be assigned to fields
            // when document is being parsed.
            // Available groups: A, B, C and D.
            'rank' => [
                'fields' => [
                    'title' => 'A',
                    'full_text' => 'C',
                    'tags' => 'B'
                ],
                // Ranking weights for searches.
                // [D-weight, C-weight, B-weight, A-weight].
                // Default [0.1, 0.2, 0.4, 1.0].
                'weights' => [0.1, 0.2, 0.1],
                // Ranking function [ts_rank | ts_rank_cd]. Default ts_rank.
                'function' => 'ts_rank',
                // Normalization index. Default 0.
                'normalization' => 32,
            ],
        ];
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'full_text' => $this->full_text,
//          Here I get the entry from the article table, in it I get all the tags in relation to this record,
//          I collect them in a line separated by a comma and form the tags field for the index
            'tags' => $this->tags()
                ->pluck('name')
                ->implode(', ')
        ];
    }
}
