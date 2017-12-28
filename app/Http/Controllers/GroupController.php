<?php
namespace App\Http\Controllers;

use Auth;

use App\Group;

use App\Events\Group\Сreate as GroupСreateEvent;
use App\Events\Group\Update as GroupUpdateEvent;
use App\Events\Group\Delete as GroupDeleteEvent;

use App\Http\Requests\Group\Create as GroupCreateRequest;
use App\Http\Requests\Group\Update as GroupUpdateRequest;

use App\Http\Resources\Group\UserList as GroupUserListResourse;

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

    public function update(GroupUpdateRequest $request, Group $group)
    {
        $data = $request->only('name');
        if (Auth::user()->isGroupOwner($group)) {
            $group->name = $data['name'];
            $group->save();

            event(new GroupUpdateEvent($group));

            return response()->success(compact($group),'Group updated', 202);
        } else {
            return response()->error('You are now owner of this group', 403);
        }
    }

    public function delete(Group $group)
    {
        if (Auth::user()->isGroupOwner($group)) {
            $group->delete();
            
            event(new GroupDeleteEvent($group));

            return response()->success([],'Group deleted', 202);
        } else {
            return response()->error('You are now owner of this group', 403);
        }
    }

    public function getGroupUsers(Group $group)
    {
        $authUser = Auth::user();
        if ( ! $group->users->contains('id', $authUser->id)) {
            return response()->error('You are not user of this group', 403);
        } else {
            $users = GroupUserListResourse::collection($group->users);

            return response()->success(compact('users'), '', 200);
        }
    }

}
