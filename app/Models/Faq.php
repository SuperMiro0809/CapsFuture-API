<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Translation;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'order'
    ];

    public function translations() {
        return $this->morphMany(Translation::class, 'parent', 'model');
    }
}
