<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\Resource;

class UserList extends Resource
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
            'status' => $this->status,
            'isOwner' => $this->whenPivotLoaded('user_group', function () {
                return ! empty($this->pivot->is_owner);
            }),
        ];
    }
}
