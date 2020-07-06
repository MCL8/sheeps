<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        for ($i = 1; $i <= 10; ++$i) {
            DB::table('sheep')->insert([
                'corral' => random_int(1, 4),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
