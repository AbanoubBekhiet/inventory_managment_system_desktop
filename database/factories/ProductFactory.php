<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
      public function definition(): array
    {
        return [
            'u_id' => 3,
            'cat_id' => 4,
'name' => $this->faker->unique()->uuid(),
            'n_pieces_in_packet' => 1,
            'original_packet_price' => 1,
            'selling_packet_price' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'accept_pieces' => 1,
                    'piece_price' => 1, 

            'selling_customer_piece_price' => 1,
            'existing_number_of_pieces' => 1,
            'exicting_number_of_pieces' => 1,
        ];
    }
}
