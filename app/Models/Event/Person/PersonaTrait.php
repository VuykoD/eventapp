<?php

namespace App\Models\Event\Person;

/**
 * Class PersonaTrait.
 */
trait PersonaTrait {

	/**
     * Get user's profile image.
     */
    public function avatar()
    {
        return $this->morphMany('App\Models\Event\ProfileImage', 'persona');
    }

    /**
     * Get user's main model.
     */
    public function base()
    {
        return $this->morphMany('App\Models\Access\User\User', 'persona');
    }

    /**
     * Get fillables.
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Get fields.
     */
    public function getFields()
    {
        return $this->form_fields;
    }

    /**
     * Get full name.
     */
    public function getFullName()
    {
        if (empty($this->last_name) || empty($this->first_name)) {
            return (isset($this->base) ? $this->base->first()->name : '');
        }
        return (isset($this->first_name) ? $this->first_name . ' ' : '') 
            . (isset($this->last_name) ? $this->last_name : '');
    }

    /**
     * Get full name.
     */
    public function getUserNameAttribute()
    {
        if (empty($this->last_name) || empty($this->first_name)) {
            return (isset($this->base) ? $this->base->first()->name : '');
        }
        return (isset($this->first_name) ? $this->first_name . ' ' : '') 
            . (isset($this->last_name) ? $this->last_name : '');
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->getFullName();
    }

    /**
     * @return string
     */
    public function getProfileNameAttribute()
    {
        return $this->getFullName();
    }


    /**
     * @return string
     */
    public function getEmailAttribute()
    {
        if (empty($this->base->all())) {
            return '';
        }

        return $this->base->first()->email;
    }

}