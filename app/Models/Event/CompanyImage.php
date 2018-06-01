<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class CompanyImage extends Model
{
	use ImageTrait;

    /**
     * Get all of the orgs that are assigned this image.
     */
    public function orgs()
    {
        return $this->morphedByMany('App\Models\Event\Organization', 'logo_image');
    }

    /**
     * Get all of the event coordinators that are assigned this image.
     */
    public function coordinators()
    {
        return $this->morphedByMany('App\Models\Event\Person\EventCoordinator', 'logo_image');
    }

    /**
     * Get all of the event hosts that are assigned this image.
     */
    public function hosts()
    {
        return $this->morphedByMany('App\Models\Event\Person\EventHost', 'logo_image');
    }

    /**
     * Get all of the event vendors that are assigned this image.
     */
    public function vendors()
    {
        return $this->morphedByMany('App\Models\Event\Person\EventVendor', 'logo_image');
    }
}
