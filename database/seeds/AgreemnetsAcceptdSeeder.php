<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AgreemnetsAcceptdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('agreements_accepteds')->insert([
            'id_user' => '1',
            'id_agreement' => '1',
            'ip_adress' => '192.168.0.1',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
