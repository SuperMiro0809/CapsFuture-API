<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    ProductFile,
    Translation
};

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'price',
        'active',
        'show_on_home_page'
    ];

    public function files() {
        return $this->hasMany(ProductFile::class);
    }

    public function translations() {
        return $this->morphMany(Translation::class, 'parent', 'model');
    }
}
