<?php

namespace App\Http\Controllers\Api\V1\Ads;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::with('user')->where('is_featured', 1)->get();
        return response()->json([
            'message' => 'Success',
            'ads' => $ads
        ]);
    }
    public function show(Ad $ad)
    {
        return response()->json([
            'message' => 'Success',
            'ad' => $ad
        ]);
    }
    public function search(Request $request)
    {
        dd($request->all());
        $qry = Ad::query()
            ->with('user')
            ->when($request->model, function ($query, $model) {
                $query->where('car_make', 'like', '%' . $model . '%');
            })
            ->when($request->color, function ($query, $color) {
                $query->where('car_color', 'like', '%' . $color . '%');
            })->when(($request->millage_from && $request->millage_to),
                function ($query) use ($request) {
                    $query->whereBetween('car_millage', [(int)$request->millage_from, (int)$request->millage_to]);
                }
            )->when($request->year_from && $request->year_to, function ($query) use ($request) {
                $query->whereBetween('car_model', [(int)$request->year_from, (int)$request->year_to]);
            });

        if ($request->has('city')) {
            $qry = $qry
                ->whereHas('user', function ($query) use ($request) {
                    $query->where('city', 'like', '%' . $request->city . '%');
                });
        }
        $ads = $qry
            ->get();

        return response()->json([
            'message' => 'Success',
            'ads' => $ads,
            'filters' => $request->all(),
        ]);
    }
}
