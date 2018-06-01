<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
	use ImageTrait;

    /**
    * Get all of the owning persona models.
    */
    public function persona()
    {
		return $this->morphTo();
    }
}
