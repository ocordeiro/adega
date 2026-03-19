<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpiritResource extends JsonResource
{
    /**
     * @param  \Illuminate\Support\Collection|null  $drinkRecipes  Receitas de drinks relacionadas ao destilado.
     */
    public function __construct($resource, protected $drinkRecipes = null)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'type' => 'spirit',
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'alcohol_content' => $this->alcohol_content,
            'spirit_type' => $this->whenLoaded('spiritType', fn () => [
                'id' => $this->spiritType->id,
                'name' => $this->spiritType->name,
                'slug' => $this->spiritType->slug,
            ]),
            'country' => $this->whenLoaded('country', fn () => $this->country ? [
                'id' => $this->country->id,
                'name' => $this->country->name,
                'code' => $this->country->code,
            ] : null),
            'producer' => $this->whenLoaded('producer', fn () => $this->producer ? [
                'id' => $this->producer->id,
                'name' => $this->producer->name,
                'website' => $this->producer->website,
            ] : null),
            'drink_recipes' => $this->when($this->drinkRecipes !== null, fn () => $this->drinkRecipes->map(fn ($dr) => [
                'id' => $dr->id,
                'name' => $dr->name,
                'description' => $dr->description,
                'instructions' => $dr->instructions,
                'prep_time' => $dr->prep_time,
                'difficulty' => $dr->difficulty,
                'photo' => $dr->getFirstMediaUrl('photo', 'card') ?: null,
                'ingredients' => $dr->ingredients->map(fn ($i) => [
                    'name' => $i->name,
                    'quantity' => $i->quantity,
                    'unit' => $i->unit,
                    'spirit_name' => $i->spirit?->name,
                ]),
            ])),
            'photos' => $this->whenLoaded('media', fn () => $this->getMedia('photos')->map(fn ($m) => [
                'original' => $m->getUrl(),
                'thumb' => $m->getUrl('thumb'),
                'card' => $m->getUrl('card'),
            ])),
        ];
    }
}
