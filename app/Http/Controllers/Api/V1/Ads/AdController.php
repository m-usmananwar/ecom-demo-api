<?php

namespace App\Http\Controllers\Api\V1\Ads;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::with(['user', 'media'])->where('is_featured', 1)->get();
        return response()->json([
            'message' => 'Success',
            'ads' => $ads
        ]);
    }
    public function show($id)
    {
        $ad = Ad::with(['user', 'media'])->find($id);
        return response()->json([
            'message' => 'Success',
            'ad' => $ad
        ]);
    }
    public function search(Request $request)
    {
        $qry = Ad::query()
            ->with(['user', 'media'])
            ->when($request->query('model'), function ($query, $model) {
                $query->where('car_make', 'like', '%' . $model . '%');
            })
            ->when($request->query('color'), function ($query, $color) {
                $query->where('car_color', 'like', '%' . $color . '%');
            })->when(($request->query('millage_from') && $request->query('millage_to')),
                function ($query) use ($request) {
                    $query->whereBetween('car_millage', [(int)$request->query('millage_from'), (int)$request->query('millage_to')]);
                }
            )->when($request->query('year_from') && $request->query('year_to'), function ($query) use ($request) {
                $query->whereBetween('car_model', [(int)$request->query('year_from'), (int)$request->query('year_to')]);
            });

        if ($request->query('city')) {
            $qry = $qry
                ->whereHas('user', function ($query) use ($request) {
                    $query->where('city', 'like', '%' . $request->query('city') . '%');
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
