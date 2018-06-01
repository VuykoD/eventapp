<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\Request;

/**
 * Class ViewOrganizationRequest.
 */
class ViewOrganizationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allowMultiple(['view-backend', 'manage-own-events', 'manage-all-events']);
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
