<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Translation;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_image_path',
        'active'
    ];

    public function translations() {
        return $this->morphMany(Translation::class, 'parent', 'model');
    }
}
