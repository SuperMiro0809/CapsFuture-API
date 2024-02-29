<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Post,
    User,
    PostCommentReply
};

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'text',
        'uploaded_by'
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function replies() {
        return $this->hasMany(PostCommentReply::class, 'comment_id');
    }
}
