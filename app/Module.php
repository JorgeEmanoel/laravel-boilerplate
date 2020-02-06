<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'class_name',
        'admin_only',
        'active',
    ];

    /**
     * Get the module permissions
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function permissions()
    {
        return $this->hasMany('App\Permission');
    }

    /**
     * Get the module's enabled profiles
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany('App\Profile', 'module_profiles');
    }

    /**
     * Get the module permissions acordding to a level name
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function getLevelPermissions(string $level_name)
    {
        return $this->permissions()
            ->where('name', 'like', "$level_name.%")
            ->get();
    }
}
