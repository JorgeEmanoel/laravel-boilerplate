<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the profile's users
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_profiles');
    }

    /**
     * Get the profile's permissions
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'profile_permissions');
    }

    /**
     * Get the profile's enabled modules
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function modules()
    {
        return $this->belongsToMany('App\Module', 'module_profiles');
    }

    /**
     * Check if the profile owns some permission
     * @param App\Permission $permission
     * @return boolean
     */
    public function hasPermission(Permission $permission)
    {
        return $this->permissions()
            ->wherePivot('permission_id', $permission->id)
            ->count() > 0;
    }
}
