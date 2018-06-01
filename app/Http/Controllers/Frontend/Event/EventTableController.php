<?php

namespace App\Http\Controllers\Frontend\Event;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Event\EventRepository;
use App\Http\Requests\Event\ViewEventRequest;
use Carbon\Carbon as Carbon;

/*
 * Class EventTableController.
 */
class EventTableController extends Controller
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
        $tables = Datatables::of($this->events->getForDataTable())
            ->escapeColumns(['name'])
            ->addColumn('actions', function ($event) {
                return $event->action_buttons;
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
