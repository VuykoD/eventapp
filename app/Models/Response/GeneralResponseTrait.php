<?php

namespace App\Models\Response;

trait GeneralResponseTrait {

	public function toArray()
    {
        return (array)$this;
    }

    protected function initProps($input) {
        if (!is_array($input)) {
            return;
        }

        foreach ($input as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
