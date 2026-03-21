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
            ->map(function (Ad $ad) {
                $mediaUrl = $ad->media_type === 'image'
                    ? $ad->getFirstMediaUrl('image')
                    : $ad->getFirstMediaUrl('video');

                return [
                    'id'               => $ad->id,
                    'title'            => $ad->title,
                    'media_type'       => $ad->media_type,
                    'media_url'        => $mediaUrl,
                    'display_duration' => $ad->display_duration,
                    // backwards compat
                    'video_url'        => $ad->media_type === 'video' ? $mediaUrl : null,
                ];
            })
            ->filter(fn (array $ad) => ! empty($ad['media_url']))
            ->values();

        return response()->json(['data' => $ads]);
    }
}
