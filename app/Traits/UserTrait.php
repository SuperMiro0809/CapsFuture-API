<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

trait UserTrait {
    public function getUsers($id=null, $all=false) {
        $query = User::select(
                    'users.*',
                    'user_profile.first_name',
                    'user_profile.last_name',
                    DB::raw("CONCAT(user_profile.first_name, ' ', user_profile.last_name) as full_name"),
                    'user_profile.avatar_photo_path',
                    'roles.name as role_name'
                )
                ->with(['profile', 'role'])
                ->leftJoin('user_profile', function ($q) {
                    $q->on('user_profile.user_id', 'users.id');
                })
                ->leftJoin('roles', function ($q) {
                    $q->on('users.role_id', 'roles.id');
                });
        
        if(request()->query('full_name')) {
            $query->where('first_name', 'LIKE', '%'.request()->query('full_name').'%')
                ->orWhere('last_name', 'LIKE', '%'.request()->query('full_name').'%');
        }

        if(request()->query('email')) {
            $query->where('email', 'LIKE', '%'.request()->query('email').'%');
        }

        if(request()->query('first_name')) {
            $query->where('first_name', 'LIKE', '%'.request()->query('first_name').'%');
        }

        if(request()->query('last_name')) {
            $query->where('last_name', 'LIKE', '%'.request()->query('last_name').'%');
        }

        if(request()->query('role')) {
            $query->where('role_id', request()->query('role'));
        }

        if(request()->has(['field', 'direction'])){
            $query->orderBy(request()->query('field'), request()->query('direction'));
        }

        if($id) {
            $users = $query->where('users.id', $id)->first();
        }else if($all) {
            $users = $query->get();
        }else {
            if(request()->query('limit')) {
                $users = $query->paginate(request()->query('limit'))->withQueryString();
            }else {
                $users = $query->paginate(10)->withQueryString();
            }
        }

        return $users;
    }
}