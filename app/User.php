<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function canDo($permission, $require = FALSE)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {

                $permName = $this->canDo($permName);
                if ($permName && !$require) {
                    return TRUE;
                } else if (!$permName && $require) {
                    return FALSE;
                }
            }
            return $require;

        } else {
            foreach ($this->roles as $role) {
                foreach ($role->perms as $perm) {
                    //foo*    foobar
                    if (str_is($permission, $perm->name)) {
                        return TRUE;
                    }
                }
            }
        }

        return false;
    }

    // string  ['role1', 'role2']
    public function hasRole($name, $require = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$require) {
                    return true;
                } elseif (!$hasRole && $require) {
                    return false;
                }
            }
            return $require;
        } else {
            foreach ($this->roles as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }
}
