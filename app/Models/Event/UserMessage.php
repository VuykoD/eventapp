<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
	protected $fillable = ['body', 'persona_id'];

    /**
     * Get the completed event
     */
    public function complete_event()
    {
        return $this->hasOne('App\Models\Event\Event', 'complete_message_id');
    }

    public function notes_event()
    {
        return $this->hasOne('App\Models\Event\Event', 'notes_id');
    }
}
