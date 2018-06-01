<?php

namespace App\Models\Event;

/**
 * Class PersonaTrait.
 */
trait FieldsTrait {

    /**
     * Get fields.
     */
    public function getFields()
    {
        return $this->form_fields;
    }

}