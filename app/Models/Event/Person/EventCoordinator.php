<?php

namespace App\Models\Event\Person;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EventCoordinator extends Model
{
    use PersonaTrait,
        OrganizationTrait,
        Notifiable;

    // fillable fields
	protected $fillable = ['phone', 'alt_phone', 'title', 'last_name', 'first_name'];

    // used for USER CREATE
	protected $form_fields = [
		'phone' => [
			'placeholder' => 'Phone',
			'type' => 'text',
            'sort' => 12,
		],
		'alt_phone' => [
			'placeholder' => 'Alt Phone',
			'type' => 'text',
            'sort' => 13,
		],
		'title' => [
			'placeholder' => 'Title',
			'type' => 'text',
            'sort' => 11,
		],
	];

	public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        uasort($this->form_fields, function($elem_a, $elem_b) {
            if ( !isset($elem_a['sort']) || !isset($elem_b['sort']) ) {
                return 0;
            }
            return (int)($elem_a['sort'] - $elem_b['sort']);
        });
    }


	 /**
     * Get all of the company images for this person.
     */
    public function logos()
    {
        return $this->morphToMany('App\Models\Event\CompanyImage', 'logo_image');
    }

    /**
     * Get the hosts created by the coordinator.
     */
    public function hosts()
    {
        return $this->hasMany('App\Models\Event\Person\EventHost');
    }

    /**
     * Get the venues created by the coordinator.
     */
    public function venues()
    {
        return $this->hasMany('App\Models\Event\Venue');
    }

    /**
     * Get the events created by the coordinator.
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event\Event');
    }
}
