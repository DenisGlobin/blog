<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'title', 'message', 'user_id',
    ];
    protected $dates = [
        'created_at', 'updated_at',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
