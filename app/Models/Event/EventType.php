<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    /**
     * Get the events.
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event\Event');
    }
}
