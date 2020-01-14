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
}
