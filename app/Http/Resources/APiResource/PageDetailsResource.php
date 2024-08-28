<?php

namespace App\Http\Resources\APiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class PageDetailsResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => $this->{$lang.'_title'},
            'description' => $this->{$lang.'_description'},
            'pic'         => $this->pic,
        ];
    }
}
