<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ads\AdUpdateRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

class AdminController extends Controller
{
    public function indexUser()
    {
        $users = User::where('role', \App\Enums\Roles::VENDOR)->get();
        return response()->json([
            'message' => 'Success',
            'users' => $users
        ]);
    }
    public function showUser($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json([
            'message' => 'Success',
            'user' => $user
        ]);
    }
    public function destroyUser($id)
    {
        $ads = Ad::where('user_id', $id)->get();
        $user = User::where('id', $id)->first();
        $user->delete();
        foreach ($ads as $ad) {
            $ad->delete();
        }
        return response()->json([
            'message' => 'Success',
        ]);
    }
    public function updateUser(UpdateUserRequest $request, $id)
    {

        $user = User::where('id', $id)->first();
        $user->update($request->validated());
        if ($request->hasFile('image')) {
            $user->clearMediaCollection('profile-images');
            $user->addMediaFromRequest('image')
                ->toMediaCollection('profile-images');
        }
        return response()->json([
            'message' => 'Success',
            'user' => $user
        ]);
    }
    public function indexAd()
    {
        $ads = Ad::with('user')->get();
        return response()->json([
            'message' => 'Success',
            'ads' => $ads
        ]);
    }
    public function showAd($id)
    {
        $ad = Ad::with('user')->where('id', $id)->first();
        return response()->json([
            'message' => 'Success',
            'ad' => $ad
        ]);
    }
    public function updateAd(AdUpdateRequest $request, $id)
    {
        $ad = Ad::findOrFail($id);
        $data = $request->validated();
        $ad->update($data);
        if ($request->hasFile('images')) {
            $ad->clearMediaCollection('images');
            $fileAdders = $ad->addMultipleMediaFromRequest(['images'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('images');
                });
        }
        return response()->json([
            'message' => 'Success',
            'ad' => $ad
        ]);
    }
    public function destroyAd(Ad $ad)
    {
        $ad->delete();
        return response()->json([
            'message' => 'Success'
        ]);
    }
}
