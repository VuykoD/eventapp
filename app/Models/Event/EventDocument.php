<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventDocument extends Model
{
    protected $fillable = ['filename', 'path', 'event_id', 'user_id'];
}
