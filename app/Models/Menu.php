<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    const NUTRITION_FACTS = ['serving_size', 'serving_per_container', 'calories', 'calories_from_fat', 'total_fat', 'saturated_fat', 'trans_fat', 'cholesterol', 'sodium', 
    'total_carbohydrates', 'dietary_fiber', 'total_sugar', 'added_suger', 'protein', 'iron', 'calcium', 'more_info_en', 'more_info_ar', 'total_fat_calories', 'total_fat_2000', 
    'total_fat_2500', 'sat_fat_calories', 'sat_fat_2000', 'sat_fat_2500', 'cholesterol_calories', 'cholesterol_2000', 'cholesterol_2500', 'sodium_calories', 
    'sodium_2000', 'sodium_2500', 'total_carbohydrates_calories', 'total_carbohydrates_fat_2000', 'total_carbohydrates_fat_2500', 'dietary_fiber_calories', 
    'dietary_fiber_2000', 'dietary_fiber_2500','allergens_icon', 'allergens_en_title', 'allergens_ar_title', 'allergens_ar_description', 
    'allergens_en_description'];
    protected $table = 'menu';
    protected $fillable = [
        'ar_title', 'en_title', 'en_description', 'ar_description', 'pic', 'category_id', 'popularity', 'has_nutrition_facts', 'nutrition_facts'
    ];
    public function getPicAttribute($value)
    {
        return Custom_Image_Path('images',$value);
    }

    public function getAllergensIconAttribute($value)
    {
        return Custom_Image_Path('images',$value);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
