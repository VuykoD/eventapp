<?php

namespace App\Http\Requests\Backend\Access\Event;

use App\Http\Requests\Request;

/**
 * Class ManageRoleRequest.
 */
class ManageOrgRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('manage-all-events');
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
