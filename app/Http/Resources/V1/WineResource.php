<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'wine',
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'vintage' => $this->vintage,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'alcohol_content' => $this->alcohol_content,
            'serving_temp_min' => $this->serving_temp_min,
            'serving_temp_max' => $this->serving_temp_max,
            'rating' => $this->rating,
            'wine_type' => $this->whenLoaded('wineType', fn () => [
                'id' => $this->wineType->id,
                'name' => $this->wineType->name,
                'slug' => $this->wineType->slug,
            ]),
            'country' => $this->whenLoaded('country', fn () => [
                'id' => $this->country->id,
                'name' => $this->country->name,
                'code' => $this->country->code,
            ]),
            'region' => $this->whenLoaded('region', fn () => $this->region ? [
                'id' => $this->region->id,
                'name' => $this->region->name,
            ] : null),
            'producer' => $this->whenLoaded('producer', fn () => $this->producer ? [
                'id' => $this->producer->id,
                'name' => $this->producer->name,
                'website' => $this->producer->website,
            ] : null),
            'grape_varieties' => $this->whenLoaded('grapeVarieties', fn () => $this->grapeVarieties->map(fn ($gv) => [
                'id' => $gv->id,
                'name' => $gv->name,
                'percentage' => $gv->pivot->percentage,
            ])),
            'occasions' => $this->whenLoaded('occasions', fn () => $this->occasions->map(fn ($o) => [
                'id' => $o->id,
                'name' => $o->name,
                'icon' => $o->icon,
                'description' => $o->description,
            ])),
            'foods' => $this->whenLoaded('foods', fn () => $this->foods->map(fn ($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'category' => $f->foodCategory?->name,
                'notes' => $f->pivot->notes,
                'image' => $f->getFirstMediaUrl('image', 'thumb') ?: null,
            ])),
            'recipes' => $this->whenLoaded('recipes', fn () => $this->recipes->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'description' => $r->description,
                'instructions' => $r->instructions,
                'prep_time' => $r->prep_time,
                'difficulty' => $r->difficulty,
                'notes' => $r->pivot->notes,
                'photo' => $r->getFirstMediaUrl('photo', 'card') ?: null,
            ])),
            'photos' => $this->whenLoaded('media', fn () => $this->getMedia('photos')->map(fn ($m) => [
                'original' => $m->getUrl(),
                'thumb' => $m->getUrl('thumb'),
                'card' => $m->getUrl('card'),
            ])),
        ];
    }
}
