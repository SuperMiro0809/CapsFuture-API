<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    PostComment,
    User
};

class PostCommentReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'text',
        'uploaded_by'
    ];

    public function comment() {
        return $this->belongsTo(PostComment::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
