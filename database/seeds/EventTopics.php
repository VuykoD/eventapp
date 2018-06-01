<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

class EventTopics extends Seeder
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
                'name'       => 'Backpack Safety',
                'code'		 => 'bkpack',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Nutrition',
                'code'		 => 'nutrit',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Peak Performance',
                'code'		 => 'peakpf',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Sleep',
                'code'		 => 'sleepp',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Stress Management',
                'code'		 => 'strsmg',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Wellness Based Lifestyle',
                'code'		 => 'welife',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Workplace Ergonomics',
                'code'		 => 'wrkpeg',
                'custom'	 => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('event_topics')->insert($rows);

    }
}
