<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Group;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name', 'email', 'password', 'status', 'lang'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'user_group')->withTimestamps();
    }

    public function isGroupOwner(Group $group)
    {
        return $this->groups()->where('is_owner',1)->get()->contains('id', $group->id);
    }
}
