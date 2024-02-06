<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\LocationTrait;
use App\Models\{
    Location,
    LocationInformation,
    LocationType
};

class LocationController extends Controller
{
    use LocationTrait;

    public function index()
    {
        $locations = $this->getLocations();

        return $locations;
    }

    public function store(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $location = Location::create([
                'name' => $request->name,
                'type_id' => $request->type,
                'latitude' => $request->location['lat'],
                'longitude' => $request->location['lng'],
                'collects_caps' => $request->collects_caps,
                'collects_bottles' => $request->collects_bottles,
                'collects_cans' =>  $request->collects_cans
            ]);

            $location->information()->create([
                'user_id' => $request->user['value'],
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'working_time' => $request->working_time,
            ]);

            return $location;
        });

        return $result;
    }

    public function update(Request $request, $id)
    {
        $result = DB::transaction(function () use ($request, $id) {
            $location = Location::find($id);

            $location->update([
                'name' => $request->name,
                'type_id' => $request->type,
                'latitude' => $request->location['lat'],
                'longitude' => $request->location['lng'],
                'collects_caps' => $request->collects_caps,
                'collects_bottles' => $request->collects_bottles,
                'collects_cans' =>  $request->collects_cans
            ]);

            $location->information()->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'working_time' => $request->working_time
            ]);

            return $location;
        });

        return $result;
    }

    public function destroy($id)
    {
        $result = DB::transaction(function () use ($id) {
            $location = Location::find($id);

            $location->information()->delete();

            $location->delete();
        });

        return $result;
    }

    public function show($id)
    {
        $location = $this->getLocations($id);

        return $location;
    }

    public function getTypes()
    {
        $types = LocationType::all();

        return $types;
    }
}
