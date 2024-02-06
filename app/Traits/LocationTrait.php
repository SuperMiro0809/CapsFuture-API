<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\Models\Location;

trait LocationTrait {
    public function getLocations($id=null) {
        $query = Location::select(
                    'locations.*',
                    'location_information.user_id',
                    'location_information.first_name',
                    'location_information.last_name',
                    'location_information.phone',
                    'location_information.email',
                    'location_information.working_time',
                    'location_types.name as location_type_name',
                    'location_types.display_name as location_type_display_name',
                )
                ->with([
                    'type',
                    'information',
                    'information.user' => function ($q) {
                        $q->select(
                            'users.*',
                            'user_profile.first_name',
                            'user_profile.last_name',
                            'user_profile.avatar_photo_path'
                        )->leftJoin('user_profile', function ($qr) {
                            $qr->on('user_profile.user_id', 'users.id');
                        });
                    }
                ])
                ->leftJoin('location_information', function ($q) {
                    $q->on('location_information.location_id', 'locations.id');
                })
                ->leftJoin('location_types', function ($q) {
                    $q->on('location_types.id', 'locations.type_id');
                });

        if(request()->query('name')) {
            $query->where('locations.name', 'LIKE', '%'.request()->query('name').'%');
        }

        if($id) {
            $locations = $query->where('locations.id', $id)->first();
        }else {
            $locations = $query->get();
        }

        return $locations;
    }
}