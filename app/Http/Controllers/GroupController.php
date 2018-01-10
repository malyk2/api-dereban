<?php
namespace App\Http\Controllers;

use Auth;

use App\Group;
use App\User;

use App\Events\Group\Сreate as GroupСreateEvent;
use App\Events\Group\Update as GroupUpdateEvent;
use App\Events\Group\Delete as GroupDeleteEvent;
use App\Events\Group\AddUser as GroupAddUserEvent;

use App\Http\Requests\Group\Create as GroupCreateRequest;
use App\Http\Requests\Group\Update as GroupUpdateRequest;
use App\Http\Requests\Group\AddUser as GroupAddUser;

use App\Http\Resources\Group\UserList as GroupUserListResourse;
use App\Http\Resources\User\InviteInfo as UserInviteInfoResourse;

use App\Exceptions\ApiCustomException;

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
        $this->checkOwner($group);
        $data = $request->only('name');

        $group->name = $data['name'];
        $group->save();

        event(new GroupUpdateEvent($group));

        return response()->success(compact($group),'Group updated', 202);
    }

    public function delete(Group $group)
    {
        $this->checkOwner($group);

        $group->delete();

        event(new GroupDeleteEvent($group));

        return response()->success([],'Group deleted', 202);

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

    public function addUserToGroup(GroupAddUser $request, Group $group)
    {
        $data = $request->only('email', 'name');
        $this->checkOwner($group);
        $groupUser = User::firstOrNew(['email' => $data['email']]);
        if ( ! $groupUser->exists) {
            $groupUser->name = $data['name'];
            $groupUser->password = '';
            $groupUser->status = User::STATUS_NEW;
            $groupUser->save();
        }
        $group->addMember($groupUser);
        Auth::user()->addToInviteUsers($groupUser, $data['name']);

        event(new GroupAddUserEvent($group, $groupUser));

        $user = new UserInviteInfoResourse($groupUser);
        return response()->success(compact('user'), 'User added to group');
    }

    public function removeUser(Group $group, User $user)
    {
        $this->checkOwner($group);
        $group->removeMember($user);
        return response()->success(true, 'User deleted from group');
    }

    protected function checkOwner($group)
    {
        if ( ! Auth::user()->isGroupOwner($group)) {
            throw (new ApiCustomException())->withMessage('You are not owner of this group')->withCode(403);
        }
    }

}
