<?php

namespace App\Http\Controllers\Frontend\Event;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Services\Access\Facades\Access;
use App\Repositories\Event\EventRepository;
use App\Http\Requests\Event\ViewEventRequest;
use Carbon\Carbon as Carbon;
use App\Models\Event\Event;
use \Exception;

/**
 * Class EventTableController.
 */
class EventTableRadiusController extends Controller
{
    /**
     * @var EventRepository
     */
    protected $events;

    /**
     * @param EventRepository $events
     */
    public function __construct(EventRepository $events)
    {
        $this->events = $events;
    }

    /**
     * @param ViewEventRequest $request
     *
     * @return mixed
     */
    public function __invoke(ViewEventRequest $request)
    {
        $coords = null;
        if (isset(Access::user()->persona) && isset(Access::user()->persona->orgs)) {
            try {
                $coords = json_decode(Access::user()->persona->orgs->first()->lat_lng, true);
            }
            catch (Exception $e) {
                $coords = null;
            }
        }

        $tables = Datatables::of($this->events->getRadiusDataTable())
            ->escapeColumns(['name'])
            ->addColumn('actions', function ($event) {
                return $event->radius_action_buttons(Access::user()->persona);
            })
            ->addColumn('radius', function ($event) use($coords) {
                if (empty($coords) || empty($event->venue) || empty($event->venue->lat_lng)) {
                    return '';
                }

                if (isset($event->vendors) && isset(Access::user()->persona) && !$event->vendors->isEmpty() && $event->vendors->contains(Access::user()->persona)) {
                    return '';
                }
                $venue_coords = json_decode($event->venue->lat_lng, true);
                $dist = round(Event::distance($coords, $venue_coords));
                return isset($dist) ? $dist : '';
            })
            ->editColumn('coordinator', function ($event) {
                return (isset($event->coordinator) ? $event->coordinator->getFullName() : '');
            })
            ->editColumn('host', function ($event) {
                return $event->host_name;
            })
            ->editColumn('type', function ($event) {
                return (isset($event->type) ? $event->type->name : '');
            })
            ->editColumn('status_text', function ($event) {
                if ($event->completed && $event->status == config('event.status.completed')) {
                    return '<label class="label label-success">' . $event->status_text . '</label>';
                }

                if ($event->completed && $event->status == config('event.status.cancelled')) {
                    return '<label class="label label-warning">' . $event->status_text . '</label>';
                }

                return $event->status_text;
            })
            ->make(true);

        return $tables;
    }
}
