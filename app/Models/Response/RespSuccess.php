<?php

namespace App\Models\Response;

use \stdClass;

class RespSuccess extends stdClass
{
    use GeneralResponseTrait;

	public $success = true;
    public $message = '';

    // Constructor
    public function __construct($msg = null, $input = []) {
        $this->message = $msg ?? 'Success';

        if (!empty($input)) {
            $this->initProps($input);
        }
    }
}
