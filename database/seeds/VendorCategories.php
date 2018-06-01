<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

class VendorCategories extends Seeder
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
                'name'       => 'Alternative Medicine',
                'code'		 => 'altmed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 1, 
            ],
            [
                'name'       => 'Biometric Screenings',
                'code'		 => 'bioscr',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 2, 
            ],
            [
                'name'       => 'Brain and Body Therapy',
                'code'		 => 'bnbthr',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 3, 
            ],
            [
                'name'       => 'Cancer Awareness',
                'code'		 => 'cancaw',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 4, 
            ],
            [
                'name'       => 'Ergonomics',
                'code'		 => 'ergons',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 5, 
            ],
            [
                'name'       => 'Financial Wellness and Education',
                'code'		 => 'finedu',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 6, 
            ],
            [
                'name'       => 'Fitness',
                'code'		 => 'fitnes',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 7, 
            ],
            [
                'name'       => 'Flu Shots',
                'code'		 => 'flusht',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 8, 
            ],
            [
                'name'       => 'Massage',
                'code'		 => 'massag',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 9, 
            ],
            [
                'name'       => 'Nutrition',
                'code'		 => 'nutrit',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 10, 
            ],
            [
                'name'       => 'Skin Care',
                'code'		 => 'skcare',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 11, 
            ],
            [
                'name'       => 'Sleep Health',
                'code'		 => 'sleeph',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 12, 
            ],
            [
                'name'       => 'Smoking Cessation',
                'code'		 => 'smokcs',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 13, 
            ],
            [
                'name'       => 'Vitamins/Supplements',
                'code'		 => 'vitspl',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 14, 
            ],
            [
                'name'       => 'Weight Loss/Management',
                'code'		 => 'wtloss',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 15, 
            ],
            [
                'name'       => 'Spine Screening',
                'code'		 => 'spinsc',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'num_code'	 => 16, 
            ],
        ];

        DB::table('vendor_categories')->insert($rows);

        // $this->enableForeignKeys();
    }
}
