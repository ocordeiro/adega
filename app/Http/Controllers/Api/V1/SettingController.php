<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $setting = Setting::instance();
        $theme   = Theme::where('is_active', true)->first();

        return response()->json([
            'data' => [
                'logo_url'         => $setting->getFirstMediaUrl('logo') ?: null,
                'color_primary'    => $theme?->color_primary    ?? '#c8a96e',
                'color_secondary'  => $theme?->color_secondary  ?? '#e2c98a',
                'color_background' => $theme?->color_background ?? '#0d0d0f',
                'color_text'       => $theme?->color_text       ?? '#f5f0eb',
                'element_scale'    => $setting->element_scale,
            ],
        ]);
    }
}
