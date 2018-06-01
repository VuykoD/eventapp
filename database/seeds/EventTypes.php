<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

class EventTypes extends Seeder
{
	use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'name'       => '30 Minute Wellness Workshop',
                'code'		 => 'wel30m',
                'group_size' => null,
                'duration'	 => '00:30:00',
                'additional' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => '60 Minute Wellness Workshop',
                'code'		 => 'wel60m',
                'group_size' => 'for groups of 30 or more people',
                'duration'	 => '01:00:00',
                'additional' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => '60 Minute Wellness Workshop',
                'code'		 => 'wl60m2',
                'group_size' => 'for groups of 100 or more people',
                'duration'	 => '01:00:00',
                'additional' => 'Additional tabling/screening time: 2 hours',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => '60 Minute Wellness Workshop',
                'code'		 => 'wl60m3',
                'group_size' => 'for groups of 100 or more people',
                'duration'	 => '01:00:00',
                'additional' => 'Additional tabling/screening time: 3 hours',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => '60 Minute Wellness Workshop',
                'code'		 => 'wl60m4',
                'group_size' => 'for groups of 100 or more people',
                'duration'	 => '01:00:00',
                'additional' => 'Additional tabling/screening time: 4 hours',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Health and Wellness Fair',
                'code'		 => 'hnwfsm',
                'group_size' => 'for groups under 100 people',
                'duration'	 => '05:00:00',
                'additional' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Health and Wellness Fair',
                'code'		 => 'hnwfmd',
                'group_size' => 'for groups of 100-200 people',
                'duration'	 => '05:00:00',
                'additional' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Health and Wellness Fair',
                'code'		 => 'hnwflg',
                'group_size' => 'for groups of 200 or more people',
                'duration'	 => '05:00:00',
                'additional' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('event_types')->insert($rows);

    }
}
