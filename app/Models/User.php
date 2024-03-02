<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\{
    Role,
    UserProfile,
    CampaignAttendance
};

use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function role() {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function profile() {
        return $this->hasOne(UserProfile::class);
    }

    public function attendances() {
        return $this->hasMany(CampaignAttendance::class);
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = config('app.client_url') . '/auth/reset-password?token=' . $token . '&email=' .$this->email;
    
        $this->notify(new ResetPasswordNotification($url));
    }
}
