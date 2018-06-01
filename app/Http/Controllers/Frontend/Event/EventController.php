<?php

namespace App\Http\Controllers\Frontend\Event;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\VendorInvite;
use App\Models\Event\HostInvite;
use App\Models\Event\HostCommitment;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;
use App\Http\Requests\Event\ViewEventRequest;
use App\Http\Requests\Event\ViewPublicEventRequest;
use App\Http\Requests\Event\ManageEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Requests\Event\StoreEventRequest;
use App\Repositories\Event\EventRepository;
use App\Repositories\Event\EventHostRepository;
use App\Repositories\Event\EventVendorRepository;
use App\Repositories\Event\EventTypeRepository;
use App\Repositories\Event\EventTopicRepository;
use App\Repositories\Event\VendorCategoryRepository;
use App\Repositories\Event\VenueRepository;
use App\Repositories\Event\OrganizationRepository;
use App\Models\Event\Venue;
use App\Models\Event\Organization;
use App\Models\Event\TemplateRepo;

/**
 * Class UserController.
 */
class EventController extends Controller
{
    /**
     * @var EventRepository
     */
    protected $events;
    protected $hosts;
    protected $venues;
    protected $vendors;
    protected $event_types;
    protected $event_topics;
    protected $categories;
    protected $orgs;

    /**
     * @param EventRepository $events
     */
    public function __construct(EventRepository $events, EventHostRepository $hosts, VenueRepository $venues,
        EventTypeRepository $event_types, EventTopicRepository $event_topics, VendorCategoryRepository $categories,
        OrganizationRepository $orgs, EventVendorRepository $vendors)
    {
        $this->events = $events;
        $this->hosts = $hosts;
        $this->venues = $venues;
        $this->vendors = $vendors;
        $this->event_types = $event_types;
        $this->event_topics = $event_topics;
        $this->categories = $categories;
        $this->orgs = $orgs;
    }

    /**
     * @param ViewEventRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ViewEventRequest $request)
    {
        return view('app.event.events'); 
    }

    /**
     * @param ViewEventRequest $request
     *
     * @return json
     */
    // public function enumHosts(ViewEventRequest $request)
    // {
    //     return []; 
    // }

    /**
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function create(ManageEventRequest $request)
    {
        return view('app.event.event-create')
            ->withFmt(Event::class)
            ->withEventHostList($this->hosts->enumHosts())
            ->withVenueList($this->venues->enumVenues())
            ->withEventTypeList($this->event_types->enumEventTypes())
            ->withEventTopicList($this->event_topics->enumEventTopics())
            ->withCategoryList($this->categories->enumVendorCategories())
            ->withOrganizationList($this->orgs->enumOrganizations())
            ->withVenueFields(with(new Venue)->getFields())
            ->withOrganizationFields(with(new Organization)->getFields())
            ->withCoordinatorList(Event::all_coordinators())
            ;
    }

    /**
     * @param StoreEventRequest $request
     *
     * @return mixed
     */
    public function store(StoreEventRequest $request)
    {
        //dd( $this->events);

        $this->events->create(['data' => $request]);
        return redirect()->route('frontend.event.index')->withFlashSuccess(trans('alerts.event.created')); // TBD
    }

    /**
     * @param Event              $event
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function show(Event $event, ViewEventRequest $request)
    {
        return view('app.event.event-view')
            ->withEvent($event); // TBD
    }

    /**
     * @param Event              $event
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function edit(Event $event, ManageEventRequest $request)
    {
        return view('app.event.event-edit')
            ->withEvent($event)
            ->withEventHostList($this->hosts->enumHosts())
            ->withVenueList($this->venues->enumVenues())
            ->withEventTypeList($this->event_types->enumEventTypes())
            ->withEventTopicList($this->event_topics->enumEventTopics())
            ->withCategoryList($this->categories->enumVendorCategories())
            ->withOrganizationList($this->orgs->enumOrganizations())
            ->withVenueFields(with(new Venue)->getFields())
            ->withOrganizationFields(with(new Organization)->getFields())
            ->withCoordinatorList(Event::all_coordinators())
            ; 
    }

    /**
     * @param Event              $event
     * @param UpdateEventRequest $request
     *
     * @return mixed
     */
    public function update(Event $event, UpdateEventRequest $request)
    {
        // dd($request);
        $this->events->update($event, ['data' => $request]);

        return redirect()->route('frontend.event.index')->withFlashSuccess(trans('alerts.event.updated'));
    }

    /**
     * @param Event              $event
     * @param UpdateEventRequest $request
     *
     * @return mixed
     */
    public function complete(Event $event, UpdateEventRequest $request)
    {
        $this->events->complete($event, ['data' => $request]);

        return redirect()->route('frontend.event.index')->withFlashSuccess(trans('alerts.event.updated'));
    }

    /**
     * @param Event              $event
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function destroy(Event $event, ManageEventRequest $request)
    {
        $this->events->delete($event);

        return redirect()->route('frontend.event.index')->withFlashSuccess(trans('alerts.event.deleted'));
    }  

    /**
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function newHost(ManageEventRequest $request)
    {
        return $this->hosts->newHost(['data' => $request]);
    }

    /**
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function newVendor(ManageEventRequest $request)
    {
        return $this->vendors->newVendor(['data' => $request]);
    }

    /**
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function newVenue(ManageEventRequest $request)
    {
        return $this->venues->newVenue(['data' => $request]);
    }

    /**
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function newOrganization(ManageEventRequest $request)
    {
        return $this->orgs->newOrganization(['data' => $request]);
    }

    /**
     * @param Event              $event
     * @param UpdateEventRequest $request
     *
     * @return mixed
     */
    public function updateVendors(Event $event, UpdateEventRequest $request)
    {
        return $this->events->updateVendors($event, ['data' => $request]);
    }

    /**
     * @param Event              $event
     * @param UpdateEventRequest $request
     *
     * @return mixed
     */
    public function showVendors(Event $event, UpdateEventRequest $request)
    {
        // dd($request);
        return view('app.event.vendor-assign')
            ->withEvent($event)
            ->withOrganizationList($this->orgs->enumOrganizations())
            ->withCategoryList($this->categories->enumVendorCategories())
            ;
    }

    /**
     * @param Event              $event
     * @param UpdateEventRequest $request
     *
     * @return mixed
     */
    public function showComplete(Event $event, UpdateEventRequest $request)
    {
        // dd($request);
        return view('app.event.event-complete')
            ->withEvent($event)
            ;
    }

    /**
     * @param UID                $uid
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function showPublic($uid, ViewPublicEventRequest $request)
    {
        if (empty($uid)) {
            return view('errors.404');
        }

        $event = Event::where('slug', $uid)->first();
        if (isset($event)) {
            return view('app.event.event-view')
                ->withEvent($event); 
        }

        return view('errors.404');
    }


    /**
     * @param int              $uid
     * @param ViewPublicEventRequest $request
     *
     * @return mixed
     */
    public function confirmVendor($uid, ViewPublicEventRequest $request)
    {
        if ($request->expectsJson()) {
            return VendorInvite::accept_invitation($uid);
        }

        return view('app.event.vendor-invite')
            ->withAction(config('event.action.vendor.confirm'))
            ->withSubTitle('Confirm Vendor')
            ->withInvite(VendorInvite::accept_invitation($uid))
            ;
    }

    /**
     * @param int              $uid
     * @param ViewPublicEventRequest $request
     *
     * @return mixed
     */
    public function declineVendor($uid, ViewPublicEventRequest $request)
    {
        
        return view('app.event.vendor-invite')
            ->withAction(config('event.action.vendor.decline'))
            ->withSubTitle('Decline Vendor')
            ->withInvite(VendorInvite::decline_invitation($uid))
            ;
    }


    /**
     * 
     * @param ViewPublicEventRequest $request
     *
     * @return mixed
     */
    public function viewConfirmHost($uid, ViewPublicEventRequest $request)
    {
        return view('app.event.host-invite')
            ->withEvent(HostInvite::check_invitation($uid)->event)
            ->withUid($uid)
            ;
    }

    public function confirmHost($uid, ViewPublicEventRequest $request)
    {
        $resp = HostInvite::accept_invitation($uid);

        if (!$resp->success && !$request->expectsJson()) {
            return redirect()->route('frontend.user.dashboard')->withFlashError($resp->message);
        }

        if (!$resp->success && $request->expectsJson()) {
            return $resp;
        }

        $resp->action = config('event.action.host.confirm');
        HostCommitment::add_response($resp, $request);

        if ($request->expectsJson()) {
            $resp->href = route('frontend.user.dashboard');
            return $resp;
        }

        return redirect()->route('frontend.user.dashboard')->withFlashSuccess('Host Confirmed');
    }

    public function declineHost($uid, ViewPublicEventRequest $request)
    {
        $resp = HostInvite::decline_invitation($uid);

        if (!$resp->success) {
            return redirect()->route('frontend.user.dashboard')->withFlashError($resp->message);
        }

        $resp->action = config('event.action.host.decline');
        HostCommitment::add_response($resp, $request);

        return redirect()->route('frontend.user.dashboard')->withFlashWarning('Host Declined');
    }


    /**
     * @param Event              $event
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function postpone(Event $event, ManageEventRequest $request)
    {
        $event->status = config('event.status.postponed');
        $event->save();
        $resp = $event->notifyAll(config('event.action.postpone'));
        return redirect()->route('frontend.event.index')->withFlashSuccess($resp->message);
    }

     public function pending(Event $event, ManageEventRequest $request)
    {
        $event->status = config('event.status.pending');
        $event->save();
        $resp = $event->notifyAll(config('event.action.pending'));

        return redirect()->route('frontend.event.index');
    }   

    /**
     * @param Event              $event
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function cancel(Event $event, ManageEventRequest $request)
    {
        $event->completed = 1;
        $event->status = config('event.status.cancelled');
        $event->save();
        $resp = $event->notifyAll(config('event.action.cancel'));

        return redirect()->route('frontend.event.index')->withFlashSuccess($resp->message);
    }

    /**
     * @param Event              $event
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function inviteVendor(Event $event, ViewPublicEventRequest $request)
    {
        $event->requestVendorInvite(access()->user()->persona);
        return redirect()->route('frontend.user.dashboard')->withFlashSuccess('Request sent');
    }


    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function notes(Event $event, ManageEventRequest $request)
    {
        return view('app.event.event-notes')
            ->withEvent($event)
            ;
    }

    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function updateNotes(Event $event, ManageEventRequest $request)
    {
        return $event->set_notes($request->input('notes'));
    }

    /**
     * @param Event              $event
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function emailAll(Event $event, ManageEventRequest $request)
    {
        return $event->notifyAll('custom', $request->input('email_message'));
    }

    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function uploadDocs(Event $event, ManageEventRequest $request)
    {
        return $event->upload_doc($request);
    }

    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function uploadAvatar(User $user, ViewPublicEventRequest $request)
    {
        return $user->upload_avatar($request);
    }

    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function uploadLogo(Organization $org, ViewPublicEventRequest $request)
    {
        return $org->upload_logo($request);
    }

    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function viewTpl(ManageEventRequest $request)
    {
        return view('app.event.tpl-edit');
    }

    /**
     * 
     * @param ManageEventRequest $request
     *
     * @return mixed
     */
    public function updateTpl(ManageEventRequest $request)
    {
        TemplateRepo::update_all($request->only('tpl'));
        return redirect()->route('frontend.event.index')->withFlashSuccess('Templates updated');
    }
    
}
