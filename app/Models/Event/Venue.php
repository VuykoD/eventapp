<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{

	use FieldsTrait;

	// fillable fields
	protected $fillable = ['name', 'address_1', 'address_2', 'city', 'state', 'zip', 'phone', 'website', 'lat_lng'];

	protected $form_fields = [
		'name' => [
			'placeholder' => 'Venue Name',
			'type' => 'text',
            'sort' => 11,
            'attributes' => [
            	'required' => '',
            ],
		],
		'address_1' => [
			'placeholder' => 'Address 1',
			'type' => 'text',
            'sort' => 12,
            'attributes' => [
            	'required' => '',
            ],
		],
		'address_2' => [
			'placeholder' => 'Address 2',
			'type' => 'text',
            'sort' => 13,
            'attributes' => [
            ],
		],
		'city' => [
			'placeholder' => 'City',
			'type' => 'text',
            'sort' => 14,
            'attributes' => [
            	'required' => '',
            ],
		],
		'state' => [
			'placeholder' => 'State',
			'type' => 'text',
            'sort' => 15,
            'attributes' => [
            ],
		],
		'zip' => [
			'placeholder' => 'Zip',
			'type' => 'text',
            'sort' => 16,
            'attributes' => [
            ],
		],
		'phone' => [
			'placeholder' => 'Phone',
			'type' => 'text',
            'sort' => 17,
            'attributes' => [
            ],
		],
		'website' => [
			'placeholder' => 'Website',
			'type' => 'text',
            'sort' => 18,
            'attributes' => [
            ],
		],
		'lat_lng' => [
			'placeholder' => '',
			'type' => 'hidden',
            'sort' => 100,
            'attributes' => [
            	'id' => 'js-venue-coord'
            ],
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
     * @return string
     */
    public function getPublicNameAttribute()
    {
    	$addr = trim($this->address_1 . ' ' . $this->address_2);
        return sprintf('%s (%s, %s)', $this->name, $addr, $this->city);
    }


    /**
     * Get the events.
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event\Event');
    }

    /**
     * Get the coordinator that created the host.
     */
    public function coordinator()
    {
        return $this->belongsTo('App\Models\Event\Person\EventCoordinator', 'event_coordinator_id');
    }

}
