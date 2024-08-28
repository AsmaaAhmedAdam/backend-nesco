<?php

namespace App\Http\Resources\APiResource;

use App\Models\Admin;
use App\Models\Auction_Images;
use App\Models\Categories;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class Product_Details extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $lang = getallheaders()['language'];

        $size = Size::where('id',$this->size_id)->first();
        $category = Categories::where('id',$this->category_id)->first();

        return [
            'id' => $this->id,
            'title' => $this->{$lang.'_title'},
            'category' => $category != null ? $category->{$lang.'_title'} : '',
            'price_before_discount' => $this->price_before_discount,
            'price' => $this->price,
            'size' => $size != null ? $size->title : '',
            'description' => $this->{$lang.'_description'},
            'image' => $this->pic,

        ];
    }
}
