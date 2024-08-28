<?php

namespace App\Http\Resources\APiResource;

use Illuminate\Http\Resources\Json\JsonResource;
class Notications_Data_R extends JsonResource
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
            'seen'        => $this->seen,
            'type'        => $this->type,
            'item_id'     => $this->item_id,
        ];
    }
}
