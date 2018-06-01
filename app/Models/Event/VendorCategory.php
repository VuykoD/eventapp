<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{



    /**
     * The related events.
     */
    public function events()
    {
        return $this->belongsToMany('App\Models\Event\Event', 'pivot_event_category', 'vendor_category_id', 'event_id')
        	->withPivot('slots');
    }


    /**
     * The vendors of the category.
     */
    public function vendors()
    {
        return $this
            ->belongsToMany('App\Models\Event\Person\EventVendor', 'pivot_category_vendor', 'category_id', 'vendor_id');
    }

    /**
     * @return array
     */
    public function getVendorsListAttribute()
    {
        if (empty($this->vendors)) {
            return [];
        }

        $resp = [];
        foreach ($this->vendors as $vendor) {
            $resp[$vendor->id] = $vendor->full_name;
        }

        return $resp;
    }
}
