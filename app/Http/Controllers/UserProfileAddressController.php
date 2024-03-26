<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    UserProfile,
    UserProfileAddress
};

class UserProfileAddressController extends Controller
{
    public function index($profileId)
    {
        $userProfile = UserProfile::find($profileId);

        return $userProfile->addresses;
    }

    public function store(Request $request, $profileId)
    {
        $userProfile = UserProfile::find($profileId);

        if ($request->primary) {
            $userProfile->addresses()->update(['primary' => 0]);
        }

        $address = $userProfile->addresses()->create([
            'full_name' => $request->fullName,
            'phone' => $request->phone,
            'country' => $request->country['label'],
            'country_code' => $request->country['value'],
            'city' => $request->city['label'],
            'econt_city_id' => $request->city['value'],
            'quarter' => $request->quarter,
            'post_code' => $request->postCode,
            'street' => $request->street,
            'street_number' => $request->streetNumber,
            'building_number' => $request->buildingNumber,
            'entrance' => $request->entrance,
            'floor' => $request->floor,
            'apartment' => $request->apartment,
            'primary' => $request->primary,
            'note' => $request->note
        ]);

        return $address;
    }

    public function update(Request $request, $profileId, $id)
    {
        $userProfile = UserProfile::find($profileId);
        $address = UserProfileAddress::find($id);

        if ($request->has('primary')) {
            if ($request->primary) {
                $userProfile->addresses()->update(['primary' => 0]);
            }

            $address->update(['primary' => $request->primary]);
        }

        $fieldsToUpdate = $request->only([
            'phone', 'quarter', 'street',
            'entrance', 'floor', 'apartment', 'note'
        ]);

        if ($request->has('fullName')) {
            $fieldsToUpdate['full_name'] = $request->fullName;
        }
        if ($request->has('postCode')) {
            $fieldsToUpdate['post_code'] = $request->postCode;
        }
        if ($request->has('streetNumber')) {
            $fieldsToUpdate['street_number'] = $request->streetNumber;
        }
        if ($request->has('buildingNumber')) {
            $fieldsToUpdate['building_number'] = $request->buildingNumber;
        }

        if ($request->has('country')) {
            $fieldsToUpdate['country'] = $request->country['label'] ?? null;
            $fieldsToUpdate['country_code'] = $request->country['value'] ?? null;
        }
        if ($request->has('city')) {
            $fieldsToUpdate['city'] = $request->city['label'] ?? null;
            $fieldsToUpdate['econt_city_id'] = $request->city['value'] ?? null;
        }

        $address->update($fieldsToUpdate);

        return $address;
    }

    public function destroy($profileId, $id)
    {
        $address = UserProfileAddress::find($id);

        $address->delete();

        return 'Delete successful';
    }
}
