<?php

namespace App\Http\Resources\APiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lang = app()->getLocale();
        return [
            'id'                           => $this->id,
            'title'                        => $this->{$lang.'_title'},
            'image'                        => $this->pic,
            'category'                     => $this->category != null ? $this->category->{$lang.'_title'} : '',
            'has_nutrition_facts'          => $this->has_nutrition_facts ?? 0,
            'nutrition_facts'              => json_decode($this->nutrition_facts),
            'description'                  => $this->{$lang.'_description'} ?? '',
        ];
    }
}
