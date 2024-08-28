<?php

namespace App\Http\Resources\APiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class AllPagesDetailsResource extends JsonResource
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
            'id'           => $this->id,
            'main_title'   => $this->{'main_title_'.$lang},
            'titles'       => json_decode($this->{$lang.'_title'}),
            'path'         => 'https://filterr.net/dashboard/public/images/',
            'pics'         => json_decode($this->pic),
            'descriptions' => json_decode($this->{$lang.'_description'})
        ];
    }
}

