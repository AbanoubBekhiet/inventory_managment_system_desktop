<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    Product::factory()->count(2000)->create();

        // User::factory()->create([
        //     'name' => 'ميلاد بخيت',
        //     'password' => Hash::make("123456789"),
        // ]);
    }
}
