<?php

namespace App\Models\Event\Person;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EventVendor extends Model
{
    use PersonaTrait,
        OrganizationTrait,
        Notifiable;
    
    // fillable fields
	protected $fillable = ['last_name', 'first_name', 'phone', 'alt_phone', 'title', 'about_us'];

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
		'about_us' => [
			'placeholder' => 'About Us',
			'type' => 'textarea',
			'sort' => 14,
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
     * The events.
     */
    public function events()
    {
        return $this
            ->belongsToMany('App\Models\Event\Event', 'pivot_event_vendor', 'vendor_id', 'event_id')
            ->withPivot('vendor_category_id', 'confirmed', 'status');
    }

    /**
     * @return array
     */
    public function getEventsListAttribute()
    {
        if (empty($this->events)) {
            return [];
        }

        $resp = [];
        foreach ($this->events as $event) {
            $resp[$event->id] = $event->name;
        }

        return $resp;
    }

    /**
     * The topics of the event.
     */
    public function categories()
    {
        return $this
            ->belongsToMany('App\Models\Event\VendorCategory', 'pivot_category_vendor', 'vendor_id', 'category_id');
    }

    /**
     * @return array
     */
    public function getCategoriesListAttribute()
    {
        if (empty($this->categories)) {
            return [];
        }

        $resp = [];
        foreach ($this->categories as $category) {
            $resp[$category->id] = $category->name;
        }

        return $resp;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (empty($this->organization())) {
            return $this->getFullName();
        }
    
        return $this->organization()->name . ' (' . $this->getFullName() . ')';
    }

}
