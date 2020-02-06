<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'module_id',
    ];

    /**
     * Get the module owner of this permission
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    /**
     * Get the permission's profiles
     * @return Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function profiles()
    {
        return $this->belongsToMany('App\Profile', 'profile_permissions');
    }

    /**
     * Get the permission's name
     * @return string
     */
    public function getName()
    {
        return ucfirst(strtolower(explode('.', $this->name)[1]));
    }
}
