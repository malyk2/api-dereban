<?php
namespace App\Http\Controllers;

use Auth;

//use App\User;
use App\Group;

use App\Events\Group\Сreate as GroupСreateEvent;

use App\Http\Requests\Group\Create as GroupCreateRequest;

class GroupController extends Controller
{
    public function create(GroupCreateRequest $request)
    {
        $data = $request->only('name');
        $data['status'] = Group::STATUS_NEW;
        $group = Group::create($data);

        $group->users()->attach(Auth::user()->id, ['is_owner' => true]);

        event(new GroupСreateEvent($group));
        
        return response()->success(compact('group'), 'Group created', 201);
    }

    public function getAllUsersGroups()
    {
        $user = Auth::user();
        $groups = $user->groups;
        
        return response()->success(compact('groups'), '', 200);
    }
}
