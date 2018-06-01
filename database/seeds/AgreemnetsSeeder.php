<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AgreemnetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agreements')->insert([
            'name' => 'General',
            'body' => 'Welcome',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
