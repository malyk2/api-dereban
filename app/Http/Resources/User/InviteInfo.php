<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\Resource;

class InviteInfo extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'inviteName' => $this->invite_name,
            'email' => $this->email,
            'avatar' => $this->avatar,
        ];
    }
}
