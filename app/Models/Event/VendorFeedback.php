<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class VendorFeedback extends Model
{

	protected $fillable = ['rating', 'attendance', 'comments']; 

    /**
     * The event
     */
    public function events()
    {
        return $this
            ->belongsToMany('App\Models\Event\Event', 'pivot_feedback_vendor', 'feedback_id', 'event_id')
            ->withPivot('vendor_id');
    }

    /**
     * Get event
     * @return string
     */
    public function getEventAttribute()
    {
        return $this->events()->first();
    }

    /**
     * The vendor
     */
    public function vendors()
    {
        return $this
            ->belongsToMany('App\Models\Event\Person\EventVendor', 'pivot_feedback_vendor', 'feedback_id', 'vendor_id')
            ->withPivot('event_id');
    }

    /**
     * Get vendor
     * @return string
     */
    public function getVendorAttribute()
    {
        return $this->vendors()->first();
    }
}
