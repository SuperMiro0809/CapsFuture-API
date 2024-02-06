<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\UserTrait;
use App\Models\{
    User,
    UserProfile,
    Role
};

class UserController extends Controller
{
    use UserTrait;

    public function index()
    {
        $users = $this->getUsers();

        return $users;
    }

    public function store(Request $request)
    {
        $role = json_decode($request->role, true);

        $result = DB::transaction(function () use ($request, $role) {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $role["value"]
            ]);

            $user_profile = UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name
            ]);

            if($avatar_photo = $request->file('avatar_photo')) {
                $avatar_photo_path = $avatar_photo->store('users/' . $user->id, 'public');

                $user_profile->update(['avatar_photo_path' => $avatar_photo_path]);
            }

            return $user;
        });

        return $result;
    }

    public function update(Request $request, $id)
    {
        $role = json_decode($request->role, true);

        $result = DB::transaction(function () use ($request, $id, $role) {
            $user = User::find($id);
            
            $user->update([
                'email' => $request->email,
                'role_id' => $role['value']
            ]);

            $user->profile()->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            if($avatar_photo = $request->file('avatar_photo')) {
                Storage::delete('public/' . $user->profile->avatar_photo_path);
                $avatar_photo_path = $avatar_photo->store('users/' . $user->id, 'public');

                $user->profile()->update(['avatar_photo_path' => $avatar_photo_path]);
            }

            return $user;
        });

        return $result;
    }

    public function destroy($id)
    {
        $result = DB::transaction(function () use ($id) {
            $user = User::find($id);

            $user->profile()->delete();

            Storage::deleteDirectory('public/users/' . $id);

            $user->delete();

            return 'Delete successful';
        });

        return $result;
    }

    public function deleteMany(Request $request)
    {
        $ids = $request->ids;

        $result = DB::transaction(function () use ($id) {
            foreach($ids as $id) {
                $user = User::find($id);

                $product->profile()->delete();
    
                Storage::deleteDirectory('public/users/' . $id);
    
                $user->delete();
            }

            return 'Delete successful';
        });

        return $result;
    }

    public function show($id)
    {
        $user = $this->getUsers($id);

        return $user;
    }

    public function getAll()
    {
        $users = $this->getUsers(null, true);

        return $users;
    }
}
