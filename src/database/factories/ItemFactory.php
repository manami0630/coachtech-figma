<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'brand_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'image' => 'public/img/default.jpg',
            'user_id' => 1,
            'condition' => '良好',
        ];
    }
}