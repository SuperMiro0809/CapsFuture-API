<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    User,
    UserProfileAddress
};

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'avatar_photo_path'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function addresses() {
        return $this->hasMany(UserProfileAddress::class, 'profile_id');
    }
}
