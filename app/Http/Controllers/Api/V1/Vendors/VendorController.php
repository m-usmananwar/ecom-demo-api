<?php

namespace App\Http\Controllers\Api\V1\Vendors;

use App\Models\Ad;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Ads\AdStoreRequest;
use App\Http\Requests\Ads\AdUpdateRequest;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ad::with(['user', 'media'])->where('user_id', Auth::user()->id)->get();
        if (!$ads) {
            return response()->json([
                'message' => 'No Ads Exists'
            ]);
        }
        return response()->json([
            'message' => 'Success',
            'ads' => $ads
        ]);
    }
    public function store(AdStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $ad = Ad::create($data);
        if ($request->hasFile('images')) {
            $fileAdders = $ad->addMultipleMediaFromRequest(['images'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('ads-images');
                });
        }
        return response()->json([
            'message' => 'Success',
            'ad' => $ad
        ]);
    }
    public function show($id)
    {
        $ad = Ad::with(['user', 'media'])->where('id', $id)->first();
        $this->authorize('view', $ad);
        return response()->json([
            'message' => 'Success',
            'ad' => $ad
        ]);
    }
    public function update(AdUpdateRequest $request, $id)
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
    public function destroy(Ad $ad)
    {
        $this->authorize('delete', $ad);
        $ad->delete();
        return response()->json([
            'message' => 'Success'
        ]);
    }
}
