<?php

namespace App\Models\Event\Traits;

use Carbon\Carbon;

/**
 * Class EventMutatorsTrait.
 */
trait EventMutatorsTrait {

    /**
     * Get start time.
     *
     * @param  string  $value
     * @return string
     */
    public function getStartTimeAttribute($value)
    {
        return (!empty($value) ? Carbon::parse($value)->format(self::FORMAT_TIME_DISPLAY) : $value);
    }

    /**
     * Set start time.
     *
     * @param  string  $value
     * @return string
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = !empty($value) 
            ? Carbon::createFromFormat(self::FORMAT_TIME_DISPLAY, $value)
                ->format(self::FORMAT_TIME_MYSQL)
            : $value;
    }

    /**
     * Get end time.
     *
     * @param  string  $value
     * @return string
     */
    public function getEndTimeAttribute($value)
    {
        return (!empty($value) ? Carbon::parse($value)->format(self::FORMAT_TIME_DISPLAY) : $value);
    }

    /**
     * Set end time.
     *
     * @param  string  $value
     * @return string
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = !empty($value) 
            ? Carbon::createFromFormat(self::FORMAT_TIME_DISPLAY, $value)
                ->format(self::FORMAT_TIME_MYSQL)
            : $value;
    }

    /**
     * Get Event Date.
     *
     * @param  string  $value
     * @return string
     */
    public function getEventDateAttribute($value)
    {
        return (!empty($value) ? Carbon::parse($value)->format(self::FORMAT_DATE_DISPLAY) : $value);
    }

    /**
     * Set Event Date.
     *
     * @param  string  $value
     * @return string
     */
    public function setEventDateAttribute($value)
    {
        $this->attributes['event_date'] = !empty($value) 
            ? Carbon::createFromFormat(self::FORMAT_DATE_DROPPER, $value)
                ->format(self::FORMAT_DATE_MYSQL)
            : $value;
    }

    /**
     * Get status text
     *
     * @return string
     */
    public function getStatusTextAttribute()
    {
        return config('event.status_text')[$this->status] ?? config('event.status_text')[0];
    }


    /**
     * Set Completed.
     *
     * @param  string  $value
     * @return string
     */
    public function setCompletedAttribute($value)
    {
        $value = (int)$value;
        if ($value === 1) {
            $this->attributes['status'] = config('event.status.completed');
        }
        $this->attributes['completed'] = $value;
    }

}