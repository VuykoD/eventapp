<?php

namespace App\Models\Response;

use \stdClass;

class RespFailure extends stdClass
{
    use GeneralResponseTrait;

	public $success = false;
    public $message = '';

    // Constructor
    public function __construct($msg = '', $input = []) {
        $this->message = $msg;

        if (!empty($input)) {
            $this->initProps($input);
        }
    }
}

