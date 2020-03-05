<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable = [ 'body', 'user_id', 'post_id', 'approved' ];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
