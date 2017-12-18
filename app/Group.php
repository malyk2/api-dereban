<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name', 'status'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_group')->withTimestamps();
    }
}
