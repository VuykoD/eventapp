<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\VendorInvite;
use App\Models\Event\HostInvite;
use Carbon\Carbon;
use App\Services\Access\Facades\Access;
use App\Models\Event\Person\EventCoordinator;
use App\Models\Event\Person\EventVendor;
use App\Models\Event\Person\EventHost;
use App\Models\Event\VendorFeedback;
use App\Models\Event\UserMessage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralMail;
use App\Notifications\DoubleActionMail;

/**
 * Class EventRepository.
 */
class EventRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Event::class;

    /**
     * @var EventTopicRepository
     */
    protected $topics;

    protected $i;

    /**
     * @param EventTopicRepository $topics
     */
    public function __construct(EventTopicRepository $topics)
    {
        $this->topics = $topics;
        $this->i = self::MODEL;
    }

    /**
     *
     * @return mixed
     */
    public function getForDataTable($upcoming = false)
    {
        $class = self::MODEL;
        $table = with(new $class)->getTable();

        // DO ACCESS control here if needed
        // $this or user()->events

        // $dataTableQuery = [];

        if (Access::allowMultiple(['manage-all-events'])) {
            $query = $this->query()
                ->with('coordinator', 'venue', 'type')
                ->select([
                    "$table.id",
                    "$table.name",
                    "$table.event_date",
                    "$table.start_time",
                    "$table.end_time",
                    "$table.created_at",
                    "$table.event_coordinator_id",
                    "$table.status",
                    "$table.completed",
                ]);

            if ($upcoming) {
                $query = $query->where('completed', '0');
            }

            return $query->get();
        }

        if (Access::allowMultiple(['manage-own-events', 'view-events']) && !empty(access()->user()->persona)) {
            $query = access()->user()->persona->events()
                ->with('coordinator', 'venue', 'type');

            if ($upcoming) {
                $query = $query->where('completed', '0');
            }

            return $query->get();
        }
        
        return [];
    }

    /**
     *
     * @return mixed
     */
    public function getRadiusDataTable($upcoming = true)
    {
        $class = self::MODEL;
        $table = with(new $class)->getTable();

        // DO ACCESS control here if needed
        // $this or user()->events

        // $dataTableQuery = [];

        $query = $this->query()
            ->with('coordinator', 'venue', 'type')
            ;

        if ($upcoming) {
            $query = $query->where('completed', '0');
        }

        return $query->get();
    }

    /**
     * @param Model $input
     */
    public function create($input)
    {

        $data = $input['data']->toArray();
        
        $host = isset($data['event_host']['id']) ? [$data['event_host']['id']] : [];
        $topics_id = isset($data['event_topic']['id']) ? $data['event_topic']['id'] : [];
        $topics = $this->topics->validateIds($topics_id);

        $catg_id = isset($data['category']['id']) ? $data['category']['id'] : null;
        $catg_slots = isset($data['vendor_slots']) ? $data['vendor_slots'] : null;
        $vendors = [];
        if (isset($catg_id) && isset($catg_slots)) {
            $vendors = collect($catg_id)
                ->combine($catg_slots)
                ->transform(function ($item, $key) {
                    return ['slots' => $item];
                })
                ->all();
        }

        $event = $this->createEventStub($data);
        $event->fill($data);

        // $this->checkUserByEmail($data, $user);
      
        return DB::transaction(function () use ($event, $data, $host, $topics, $vendors) {

            if ($event->save()) {
                // Update relations
                $event->hosts()->sync($host);
                $event->topics()->sync($topics);
                $event->categories()->sync($vendors);

                if (access()->user()->persona instanceof EventCoordinator) {
                    $event->coordinator()->associate(access()->user()->persona);
                }

                if (access()->admin() && isset($data['coordinator_id'])) {
                    $event->coordinator()->associate(EventCoordinator::find($data['coordinator_id']));
                }             
                $event->save();
                //$this->notifyHost(EventHost::findOrFail($host)->first(), $event);               
                return $event;
            }

            throw new GeneralException('Failed to create event');
            
        });
         
    }

    /**
     * @param Event $event
     * @param array $input
     */
    public function update(Event $event, array $input)
    {
        $data = $input['data']->toArray();
        
        $host = isset($data['event_host']['id']) ? [$data['event_host']['id']] : [];
        
        $topics_id = isset($data['event_topic']['id']) ? $data['event_topic']['id'] : [];
        $topics = $this->topics->validateIds($topics_id);

        $catg_id = isset($data['category']['id']) ? $data['category']['id'] : null;
        $catg_slots = isset($data['vendor_slots']) ? $data['vendor_slots'] : null;
        $vendors = [];
        if (isset($catg_id) && isset($catg_slots)) {
            $vendors = collect($catg_id)
                ->combine($catg_slots)
                ->transform(function ($item, $key) {
                    return ['slots' => $item];
                })
                ->all();
        }

        return DB::transaction(function () use ($event, $data, $host, $topics, $vendors) {
            if ($event->update($data)) {
                // Update relations
                $event->hosts()->sync($host);
                $event->topics()->sync($topics);
                $event->categories()->sync($vendors);

                if (access()->admin() && isset($data['coordinator_id'])) {
                    $event->coordinator()->associate(EventCoordinator::find($data['coordinator_id']));
                }

                $event->save();

                return $event;
            }

            throw new GeneralException('Failed to update event');
        });
    }


    /**
     * @param Event $event
     * @param array $input
     */
    public function complete(Event $event, array $input)
    {

        $data = $input['data']->all();

        $data['attendance'] = isset($data['att_id']) && isset(config('event.attendance')[$data['att_id']])
            ? config('event.attendance')[$data['att_id']]
            : '';

        $data['complete_message'] = $data['complete_message'] ?? '';
        $data['vendor'] = $data['vendor'] ?? [];

        return DB::transaction(function () use ($event, $data) {
            $persona_id = access()->user()->persona_id;
            $msg = UserMessage::create([
                'body' => $data['complete_message'], 
                'persona_id' => $persona_id,
            ]);

            $feedbacks = [];
            foreach ($data['vendor'] as $vendor) {
                $feedback = VendorFeedback::create([
                    'rating' => $vendor['rating'], 
                    'attendance' => config('event.vendor.attendance')[$vendor['attendance']],
                    'comments' => $vendor['comments'],
                ]);
                $feedbacks[$feedback->id] = ['vendor_id' => $vendor['id']];
            }

            if ($event->update($data)) {
                // Update relations
                $event->completed = 1;
                $event->complete_message()->associate($msg);
                $event->feedbacks()->sync($feedbacks);

                $event->save();

                return $event;
            }

            throw new GeneralException('Failed to update event');
        });
    }


    /**
     * @param Event $event
     * @param array $input
     */
    public function updateVendors(Event $event, array $input)
    {
        $data = $input['data']->all();
        // dd($data);
        $vendors_input = !empty($data['vendor']['id']) ? $data['vendor']['id'] : [];
        $action = !empty($data['action']) ? $data['action'] : '';
        $catg = !empty($data['category']) ? (int)$data['category'] : null;

        if (empty($vendors_input) || empty($catg)) {
            throw new GeneralException('Failed to update event: wrong input');
        }
        
        if (!collect(config('event.action.vendor'))->search($action)) {
            throw new GeneralException('Failed to update event: wrong input');
        }

        $status = ($action == config('event.action.vendor.invite')
            ? config('event.vendor.status.pending') 
            : ($action == config('event.action.vendor.assign') ? config('event.vendor.status.assigned') : 0));
        $confirmed = ($status == config('event.vendor.status.assigned') ? 1 : 0);

        $vendors = collect($vendors_input)
            ->mapWithKeys(function ($item) use ($catg, $status, $confirmed) {
                return [$item => ['vendor_category_id' => $catg, 'status' => $status, 'confirmed' => $confirmed]];
            })
            ->all();

        $notify_list = collect($vendors_input)
            ->map(function ($item, $key) {
                return EventVendor::find($item);
            })
            ->values()
            ->filter();

        // dd($notify_list);
        $notify_message = '';
        switch ($action) {
            case config('event.action.vendor.invite'):
                $notify_message = tpl_repo('event.email.template.vendor.confirm');
                break;

            case config('event.action.vendor.assign'):
                $notify_message = tpl_repo('event.email.template.vendor.assigned');
                break;
        }


        return DB::transaction(function () use ($event, $vendors, $catg, $notify_list, $notify_message, $action) {
            $event->vendors()->attach($vendors);

            if ($action == config('event.action.vendor.invite')) {
                foreach ($notify_list as $vendor) {
                    $uids = VendorInvite::insert_uids($vendor->id, $event->id);
                    $end_str = 'You can also enter this code in dashboard to confirm: ' . $uids['confirm'];
                    $yes_url = route('frontend.vendor.confirm.invite', $uids['confirm']);
                    $no_url = route('frontend.vendor.decline.invite', $uids['decline']);
                    Notification::send($vendor, new DoubleActionMail($notify_message, $yes_url, $no_url, $end_str));
                }
            }

            if ($action == config('event.action.vendor.assign')) {
                $visit_url = route('frontend.event.show', $event->id);
                Notification::send($notify_list, new GeneralMail($notify_message, $visit_url));
            }
            
            return ['success' => true, 'catg' => $catg, 'catg_data' => $event->categories_alldata[$catg]];
        });
    }


    /**
     * @param Event $event
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Event $event)
    {
        if ($event->delete()) {
            return true;
        }

        throw new GeneralException('Event deletion failure');
    }



    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createEventStub($input)
    {      
        $event = self::MODEL;
        $event = new $event();
        $event->name = $input['name'];
        $event->slug = $event->eventUID();
        if (array_key_exists('HostCofirmation',$input)) {
           $event->status=1;
        }else{
           $event->status=4; 
        }
        return $event;
    }
    /**
     * return array of objects
     * @return array
     */
    public function enum()
    {
        if (Access::allow('manage-all-events')) {
            return $this->i::all();
        }

        if (Access::allowMultiple(['manage-own-events', 'view-events']) && isset(access()->user()->persona)) {
            return isset(access()->user()->persona->events) ? access()->user()->persona->events->all() : [];
        }

        return [];
    }

    /**
     * return array of events for dashboard
     * @return array
     */
    public function enumDashboardEvents()
    {
        $events = $this->enum();
        $resp = [];

        foreach ($events as $event) {
            $resp[] = [
                'date' => $event->event_date,
                'title' => $event->name,
                'location' => $event->venue_name,
                'id' => $event->id,
                'route' => route('frontend.event.show', $event->id),
            ];
        }

        return $resp;
    }


    protected function notifyHost($host, $event)
    {

        dd($host);
        $uids = HostInvite::insert_uids($host->id, $event->id);
        $visit_url = route('frontend.host.confirm.view', $uids['confirm']);
        $notify_message = tpl_repo('event.email.template.host.invited');
        Notification::send($host, new GeneralMail($notify_message, $visit_url));
    }


}
