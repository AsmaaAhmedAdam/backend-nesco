<?php

namespace App\Http\Resources\ApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class AllReviewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
             'id'        => $this->id, 
            'value'      => $this->value,
            'notes'      => $this->notes,
            'created_at' => $this->created_at,
            'user_id'    => $this->user->id ?? null,
            'user_name'  => $this->user->name ?? '',
        ];
    }
}
