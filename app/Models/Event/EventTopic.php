<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventTopic extends Model
{
	// fillable fields
	protected $fillable = ['name', 'code', 'custom'];


	/**
     * Set Name.
     *
     * @param  string  $value
     * @return string
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(trim($value));
    }


    /**
     * The related events.
     */
    public function events()
    {
        return $this->belongsToMany('App\Models\Event\Event', 'pivot_event_topic', 'event_topic_id', 'event_id');
    }
}
