<?php

namespace App\Models\Event\Person;

/**
 * Class OrganizationTrait.
 */
trait OrganizationTrait {

    /**
     * Get all of the orgs that are assigned this person.
     */
    public function orgs()
    {
        return $this->morphToMany('App\Models\Event\Organization', 'persona', 'pivot_persona_org', 'persona_id', 'organization_id');
    }

    /**
     * Get first org that is assigned this person.
     */
    public function organization()
    {
        return !empty($this->orgs) ? $this->orgs->first() : null;
    }

    /**
     * Get start time.
     *
     * @param  string  $value
     * @return string
     */
    public function getOrgIdAttribute($value)
    {
        return !empty($this->organization()) ? $this->organization()->id : null;
    }

}