<?php

use App\Models\Event\Person\EventCoordinator;
use App\Models\Event\Person\EventHost;
use App\Models\Event\Person\EventVendor;
use App\Models\Event\Event;
use App\Models\Access\Role\Role;

return [

    'action' => [
        'postpone' => 'postpone',
        'cancel' => 'cancel',
        'vendor' => [
            'invite' => 'add_vendor',
            'assign' => 'assign_vendor',
            'confirm' => 'confirm',
            'decline' => 'decline',
        ],
        'host' => [
            'confirm' => 'confirm',
            'decline' => 'decline',
        ],
    ],

    'attendance' => [
        0 => 'As Expected',
        1 => 'Less Than Expected',
        2 => 'Better Than Expected',
    ],

    'avatar' => [
        'default' => 'http://www.gravatar.com/avatar/8499926abb3e2348afb663ae0482a00c.jpg?s=160&amp;d=mm&amp;r=g',
        'small' => '/assets/images/portrait/small/avatar-s-1.png',
    ],
	
	'class' => Event::class,

    'distance' => [
        25 => '25 miles',
        50 => '50 miles',
        100 => '100 miles',
        250 => '250 miles',
    ],

    'email' => [
        'template' => [
            'vendor' => [
                'confirm' => 'You have been invited to an event. <%LINK|Confirm%> Please confirm your participation.',
                'assigned' => 'You have been assigned to an event. <%LINK|View Event%> Please follow the link to view it.',
            ],
            'host' => [
                'invited' => 'Please confirm hosting of an event. <%LINK|Confirm%> Follow the link to confirm or decline.',
            ],
            'coordinator' => [
                'host_commit' => 'Host confirmed hosting of an event. <%LINK|View Event%> Follow the link to view an event.',
                'host_declined' => 'Host declined. <%LINK|View Event%> Follow the link to view an event.',
                'vendor_invite' => 'Vendor requested an invite for the following event. <%LINK|View Event%> Follow the link to view an event.',
            ],
            'event' => [
                'postponed' => 'The event was postponed. <%LINK|View Event%> Follow the link to view.',
                'cancelled' => 'The event was cancelled. <%LINK|View Event%> Follow the link to view.',
            ],
        ],
    ],

    'insurance' => [
        'plans' => [
            'HMO', 'PPO', 'HSA', 'Other'
        ],
    ],

    // Relations maps
    'rel' => [
        'morph' => [
            'coordinator' => EventCoordinator::class,
            'host' => EventHost::class,
            'vendor' => EventVendor::class,
        ],
    ],

    'status' => [
        'undefined' => 0,
        'pending' => 1,
        'postponed' => 2,
        'declined' => 3,
        'confirmed' => 4,
        'completed' => 5,
        'cancelled' => 6,
    ],

    'status_text' => [
        0 => 'Undefined',
        1 => 'Pending',
        2 => 'Postponed',
        3 => 'Declined',
        4 => 'Confirmed',
        5 => 'Completed',
        6 => 'Cancelled',
    ],

        // MODELS 
        // ************

    // Admin id
    'admin' => [
        'id' => 1,
    ],

    /*
    * event coordinator settings
    */
    'coordinator' => [
    	'class' => EventCoordinator::class,
        'meta_id' => 1,
    	'role' => [
    		'id' => 4,
	    	'name' => 'Event Coordinator',
    	],
    ],

    /*
    * event host settings
    */
    'host' => [
	    'class' => EventHost::class,
        'meta_id' => 2,
    	'role' => [
    		'id' => 6,
	    	'name' => 'Event Host',
    	],
    ],

    /*
    * event vendor settings
    */
    'vendor' => [
	    'class' => EventVendor::class,
    	'role' => [
    		'id' => 3,
	    	'name' => 'Vendor User',
    	],
        'meta_id' => 3,
        'attendance' => [
            0 => 'On Time',
            1 => 'Late',
            2 => 'No Show',
        ],
        'status' => [
            'undefined' => 0,
            'pending' => 1,
            'confirmed' => 2,
            'declined' => 3,
            'assigned' => 4,
        ],
        'status_text' => [
            0 => 'Undefined',
            1 => 'Pending',
            2 => 'Confirmed',
            3 => 'Declined',
            4 => 'Assigned',
        ],
    ],

    // model class -> name/id map
    'ev_classes' => [
        1 => [
            'name' => 'Event Coordinator',
            'class' => 'App\Models\Event\Person\EventCoordinator',
            'role_id' => 4,
        ],
        2 => [
            'name' => 'Event Host',
            'class' => 'App\Models\Event\Person\EventHost',
            'role_id' => 6,
        ],
        3 => [
            'name' => 'Event Vendor',
            'class' => 'App\Models\Event\Person\EventVendor',
            'role_id' => 3,
        ],
        4 => [
            'name' => 'Staff User',
            'class' => 'App\Models\Event\Person\StaffUser',
            'role_id' => 2,
        ],
    ],

];
