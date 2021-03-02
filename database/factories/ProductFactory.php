<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker
                ->unique()
                ->numerify('Product ###'),
            'sku' => uniqid(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'stockTimeline' => $this->generateStockTimeline(),
        ];
    }

    private function generateStockTimeline()
    {
        $count = $this->faker->numberBetween(0, 10);

        $stockTimeline = collect([]);

        for($i = 0; $i < $count; $i++) {
            $item = [
                'stock' => $this->faker->numberBetween(0, 100),
                'date' => $this->faker->dateTimeThisYear(),
            ];

            $stockTimeline->push($item);
        }

        return $stockTimeline->sortBy('date')->toJson();
    }
}
