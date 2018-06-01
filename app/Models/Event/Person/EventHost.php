<?php

namespace App\Models\Event\Person;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EventHost extends Model
{
    use PersonaTrait,
        OrganizationTrait,
        Notifiable;
    
    // fillable fields
	protected $fillable = ['phone', 'alt_phone', 'title', 'last_name', 'first_name', 'health_insurer', 'health_plans'];

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
     * Get the coordinator that created the host.
     */
    public function coordinator()
    {
        return $this->belongsTo('App\Models\Event\Person\EventCoordinator', 'event_coordinator_id');
    }

    /**
     * The hosted events.
     */
    public function events()
    {
        return $this->belongsToMany('App\Models\Event\Event', 'pivot_event_host', 'event_host_id', 'event_id');
    }

    /**
     * @return string
     */
    public function getPlansListAttribute()
    {
        if (empty($this->health_plans)) {
            return '';
        }

        return collect(explode(',', $this->health_plans))
            ->transform(function ($item, $key) {
                return config('event.insurance.plans')[$item] ?? '';
            })
            ->filter()
            ->implode(', ');
    }

     /**
     * @return array
     */
    public function getPlansArrayAttribute()
    {
        if (empty($this->health_plans)) {
            return null;
        }

        return explode(',', $this->health_plans);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (empty($this->organization())) {
            return $this->getFullName();
        }
    
        return $this->getFullName() . ' (' . $this->organization()->name . ')';
    }

}
