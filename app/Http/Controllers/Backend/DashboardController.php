<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Event\EventRepository;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{

	protected $events;

	public function __construct(EventRepository $events)
	{
		$this->events = $events;
	}

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('app.dashboard')
        	->withEventList($this->events->enumDashboardEvents())
        ;
    }
}
