<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

class VendorInvites extends Seeder
{

	use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->disableForeignKeys();

        $rows = [
            [
                'uid'            => '08427',
                'action'		 => 'confirm',
                'vendor_id'      => '5',
                'event_id'       => '17',
                'invited_at' => Carbon::now(),
                'responsed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'uid'            => '77522',
                'action'         => 'decline',
                'vendor_id'      => '5',
                'event_id'       => '17',
                'invited_at' => Carbon::now(),
                'responsed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ],
            [
                'uid'            => '52240',
                'action'         => 'unresponse',
                'vendor_id'      => '6',
                'event_id'       => '17',
                'invited_at' => Carbon::now(),
                'responsed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ],
            [
                'uid'            => '52240',
                'action'         => 'pending',
                'vendor_id'      => '6',
                'event_id'       => '17',
                'invited_at' => Carbon::now(),
                'responsed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ],
        ];

        DB::table('vendor_invites')->insert($rows);

        // $this->enableForeignKeys();
    }
}
