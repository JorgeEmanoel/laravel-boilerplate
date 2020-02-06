<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's profiles
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany('App\Profile', 'user_profiles');
    }

    /**
     * Get the user's products
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Check if user is super admin
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->admin === 1;
    }
}
