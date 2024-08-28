<?php

namespace App\Http\Resources\ApiResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileDetailsResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'email'        => $this->email,
            'status'       => $this->status ?? 1,
            'mobile'       => $this->mobile,
            'address'      => json_decode($this->address ?? ''),
            'device_token' => $this->device_token,
        ];
    }
}
