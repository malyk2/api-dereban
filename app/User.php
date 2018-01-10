<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Group;
use Illuminate\Support\Facades\Auth;

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

    /*Start relations*/
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'user_group')->withTimestamps()->withPivot('is_owner');
    }

    public function inviteUsers()
    {
        return $this->belongsToMany('App\User', 'invite_users', 'user_id', 'invaited_user_id')->withTimestamps()->withPivot('name');
    }

    public function inviteNotifies()
    {
        return $this->belongsToMany('App\Group', 'invite_notifies')->withTimestamps();
    }
    /*End relations*/

    /*Start mutators*/
    public function getInviteNameAttribute()
    {
        $authUser = Auth::user();
        $inviteUser = $authUser->inviteUsers()->find($this->id);
        return ! empty($inviteUser) ? $inviteUser->pivot->name : $this->name;
    }
    /*End mutators*/

    /*Start helper function*/
    public function isGroupOwner(Group $group)
    {
        return $this->groups()->where('is_owner',1)->get()->contains('id', $group->id);
    }

    public function addToInviteUsers(User $user, $invitedName = '')
    {
        $name = ! empty($invitedName) ? $invitedName : $user->name;
        $this->inviteUsers()->detach($user->id);
        $this->inviteUsers()->attach([$user->id => ['name' => $name]]);
    }
    /*End helper function*/
}
