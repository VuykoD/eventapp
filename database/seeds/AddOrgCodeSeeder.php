<?php

use Illuminate\Database\Seeder;
use App\Models\Event\Organization;

class AddOrgCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	foreach (Organization::all() as $org) {
    		$org->code = $this->getRandomCode();
    		$org->save();
    	}
    }

    /*
    * return string
    */
    private function getRandomCode()
    {
        $code = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $validator = \Validator::make(['code' => $code], ['code' => 'unique:organizations,code']);
        if ($validator->fails()) {
            $this->getRandomCode();
        }
        return $code;
    }
}
