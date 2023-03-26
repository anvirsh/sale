<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
		   'date' => $this->faker->date('d.m.Y'),	
		   'prod_ids' => '1_;tovar1_;2_;200__;2_;tovar2_;3_;600',//Т.е. id-тваа=1, колво=2шт. и сумма за два штуки(потом __;и след.товр)
		   //'product_id' => $this->faker->numberBetween($min = 1, $max = 10),//Из табл. products
		   'phone' => function(){
			       $one5 = $this->faker->numberBetween($min = 10000, $max = 99999);
				   $fir = $this->faker->numberBetween($min = 1000, $max = 9999);
			       return '+79'. $one5 .$fir;
				 },			 
			'email' => $this->faker->unique()->email,
			'addres' => $this->faker->address,
            'created_at' => $this->faker->dateTimeBetween('-30 days', '-25 days'),
            'updated_at' => $this->faker->dateTimeBetween('-15 days', '-5 days'),
			'total' =>  $this->faker->numberBetween($min = 3000, $max = 200000),
           ];
    }
}
