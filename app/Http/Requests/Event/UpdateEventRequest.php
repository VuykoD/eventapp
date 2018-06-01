<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\Request;

/**
 * Class UpdateEventRequest.
 */
class UpdateEventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allowMultiple(['manage-own-events', 'manage-all-events']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
