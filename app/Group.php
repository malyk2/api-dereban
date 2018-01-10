<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\ApiCustomException;

class Group extends Model
{
    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name', 'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model)
        {
            $model->users()->detach();
        });
    }

    /*Start relations*/
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_group')->withTimestamps()->withPivot('is_owner');
    }
    /*End relations*/

    /*Start helper function*/
    public function addMember(User $user)
    {
        if ($this->users->contains('id', $user->id)) {
            throw (new ApiCustomException())->withMessage('Current user is already in this group')->withCode(400);
        }
        $this->users()->attach([$user->id => ['is_owner' => false]]);
    }
    /*End helper function*/
}
