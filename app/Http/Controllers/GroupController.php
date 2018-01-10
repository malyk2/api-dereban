<?php
namespace App\Http\Controllers;

use Auth;

use App\Group;
use App\User;

use App\Events\Group\Сreate as GroupСreateEvent;
use App\Events\Group\Update as GroupUpdateEvent;
use App\Events\Group\Delete as GroupDeleteEvent;
use App\Events\Group\AddUser as GroupAddUserEvent;
// use App\Events\Group\AddUserToGroup as
// use App\Events\Group\AddRegisteredUserByEmail as GroupAddRegisteredUserByEmailEvent;
// use App\Events\Group\AddNewUserByEmail as GroupAddNewUserByEmailEvent;

use App\Http\Requests\Group\Create as GroupCreateRequest;
use App\Http\Requests\Group\Update as GroupUpdateRequest;
use App\Http\Requests\Group\AddUser as GroupAddUser;
// use App\Http\Requests\Group\AddRegisteredUserByEmail as GroupAddRegisteredUserByEmailRequest;
// use App\Http\Requests\Group\AddNewUserByEmail as GroupAddNewUserByEmailRequest;

//use App\Http\Resources\Group\UserList as GroupUserListResourse;


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

    // public function addRegisteredUserByEmail(GroupAddRegisteredUserByEmailRequest $request, Group $group)
    // {
    //     $data = $request->only('email');
    //     $authUser = Auth::user();
    //     if (Auth::user()->isGroupOwner($group)) {
    //         $user = User::where('email', $data['email'])->first();
    //         if ( $group->users->contains('id', $user->id)) {
    //             return response()->error('Current user is already in this group', 400);
    //         } else {
    //             $group->users()->attach([$user->id => [ 'is_owner' => false ]]);

    //             event(new GroupAddRegisteredUserByEmailEvent($group, $user));

    //             return response()->success([], 'User added to group');
    //         }
    //     } else {
    //         return response()->error('You are now owner of this group', 403);
    //     }
    // }

    // public function addNewUserByEmail(GroupAddNewUserByEmailRequest $request, Group $group)
    // {
    //     $data = $request->only('email', 'name');
    //     $data['name'] =  empty($data['name']) ? explode('@', $data['email'])[0] : $data['name'];
    //     $data['password'] = '';
    //     $data['status'] = User::STATUS_NEW;
    //     $user = User::create($data);
    //     $group->users()->attach([$user->id => [ 'is_owner' => false ]]);

    //     event(new GroupAddNewUserByEmailEvent($group, $user));

    //     return response()->success([], 'User added to group');
    // }

    public function addUserToGroup(GroupAddUser $request, Group $group)
    {
        $data = $request->only('email', 'name');
        if (Auth::user()->isGroupOwner($group)) {
            $groupUser = User::firstOrNew(['email' => $data['email']]);
            if ( ! $groupUser->exists) {
                $groupUser->name = $data['name'];
                $groupUser->password = '';
                $groupUser->status = User::STATUS_NEW;
                $groupUser->save();
                $groupUser->groups()->attach($group->id, [ 'is_owner' => false ]);
                Auth::user()->inviteUsers()->attach($groupUser->id, ['name' => $data['name']]);

                event(new GroupAddUserEvent($group, $groupUser));

                return response()->success('');
            } else {
                if ($group->users->contains('id', $groupUser->id)) {
                    return response()->error('Current user is already in this group', 400);
                } else {
                    $groupUser->groups()->attach($group->id, [ 'is_owner' => false ]);

                    event(new GroupAddUserEvent($group, $groupUser));
                }
            }
        } else {
            return response()->error('You are now owner of this group', 403);
        }
    }

}
