<?php

namespace App\Models\Event\Person;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StaffUser extends Model
{
	use PersonaTrait,
        Notifiable;

    protected $fillable = ['last_name', 'first_name'];

	protected $form_fields = [];

    /**
     * @param array $attributes
     */
    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
        
    //     uasort($this->form_fields, function($elem_a, $elem_b) {
    //         if ( !isset($elem_a['sort']) || !isset($elem_b['sort']) ) {
    //             return 0;
    //         }
    //         return (int)($elem_a['sort'] - $elem_b['sort']);
    //     });
    // }
}
