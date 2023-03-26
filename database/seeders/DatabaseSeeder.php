<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
		 \App\Models\Product::create([ 'name' => 'а_товар11','price' => '100',]);
		 \App\Models\Product::create([ 'name' => 'аа_товар22','price' => '200',]);
		 \App\Models\Product::create([ 'name' => 'ааа_товар33','price' => '300',]);
		 \App\Models\Product::create([ 'name' => 'a_tovar11','price' => '100',]);
		 \App\Models\Product::create([ 'name' => 'aa_tovar22','price' => '200',]);
		 \App\Models\Product::create([ 'name' => 'aaa_tovar33','price' => '300',]);
		 \App\Models\Product::create([ 'name' => 'aaaa_tovar44','price' => '400',]);
		 \App\Models\Product::create([ 'name' => 'b_tovar11','price' => '100',]);
		 \App\Models\Product::create([ 'name' => 'bb_tovar22','price' => '200',]);
		 \App\Models\Product::create([ 'name' => 'bbb_tovar333','price' => '300',]);
         \App\Models\Product::factory(10)->create();
		 \App\Models\Order::factory(10)->create();
         \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@it.it',
			'password' => '$2y$10$wBcSaXQtEV6x.RsSDkNlR.TrKf8ekgP4XcY3IQcPW2xe3DIAoqwHu',
          ]);
    }
}
