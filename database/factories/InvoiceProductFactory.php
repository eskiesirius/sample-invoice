<?php

namespace Database\Factories;

use App\Models\InvoiceProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_id' => 1,
            'name' => $this->faker->name,
            'quantity' => 1,
            'price' => 1.00
        ];
    }
}
