<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    public function index(): JsonResponse
    {
        $ads = Ad::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Ad $ad) => [
                'id'        => $ad->id,
                'title'     => $ad->title,
                'video_url' => $ad->getFirstMediaUrl('video'),
            ])
            ->filter(fn (array $ad) => ! empty($ad['video_url']))
            ->values();

        return response()->json(['data' => $ads]);
    }
}
