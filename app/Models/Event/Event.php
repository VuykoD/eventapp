<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Services\Access\Facades\Access;
use App\Models\Event\VendorCategory;
use App\Models\Event\HostCommitment;
use App\Models\Event\UserMessage;
use App\Models\Event\EventDocument;
use App\Models\Event\Person\EventVendor;
use App\Models\Event\Person\EventCoordinator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Notifications\GeneralMail;
use App\Notifications\BasicMail;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;
use \Exception;
use App\Exceptions\GeneralException;

class Event extends Model
{
	use FieldsTrait;
    use Traits\EventButtonsTrait;
    use Traits\EventMutatorsTrait;
    use Traits\UIDTrait;

    const FORMAT_DATE_DISPLAY = 'm/d/y';
    const FORMAT_DATE_DROPPER = 'm/d/Y';
    const FORMAT_TIME_DISPLAY = 'h:i a';
    const FORMAT_DATE_MYSQL = 'Y-m-d';
    const FORMAT_TIME_MYSQL = 'H:i:s';
    const FORMAT_TIME_DROPPER = 'h:mm a';

	// fillable fields
	protected $fillable = ['name', 'event_date', 'start_time', 'end_time', 'venue_id', 'event_type_id',
        'status', 'num_attendees', 'attendance', 'slug'];

	public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function notifyAll($action, $body = null)
    {
        // $coordinator = $this->coordinator;
        $hosts = $this->hosts;
        $vendors = $this->vendors;
        $notify_list = collect($hosts)
            // ->merge([$coordinator])
            ->merge($vendors)
            ->filter();

        switch ($action) {
            case config('event.action.postpone'):
                $notify_message = tpl_repo('event.email.template.event.postponed');
                break;

            case config('event.action.cancel'):
                $notify_message = tpl_repo('event.email.template.event.cancelled');
                break;

            case 'custom':
                Notification::send($notify_list, new BasicMail($body));
                return new RespSuccess;
                break;
            
            default:
                return new RespFailure('Unable to complete');
                break;
        }

        $visit_url = route('frontend.event.show', $this);

        Notification::send($notify_list, new GeneralMail($notify_message, $visit_url));

        return new RespSuccess;
    }

    public function requestVendorInvite($vendor) {
        if (!($vendor instanceof EventVendor)) {
            throw new GeneralException('Not vendor!');
        }

        if (!isset($this->coordinator)) {
            throw new GeneralException('No Event Coordinator assigned');
        }

        $notify_message = tpl_repo('event.email.template.coordinator.vendor_invite')
            . ' Vendor name ' . $vendor->full_name . ', email ' . $vendor->email;

        $visit_url = route('frontend.event.show', $this);

        Notification::send($this->coordinator, new GeneralMail($notify_message, $visit_url));
    }


    public function upload_doc($request) {
        $file = $request->file('docs');
        $filename = $file->getClientOriginalName();
        $path = Storage::disk('event_docs')->putFile(access()->user()->id, $file);
        EventDocument::create(['filename' => $filename, 'path' => $path, 'event_id' => $this->id, 'user_id' => access()->user()->id]);
        return new RespSuccess('File Uploaded');
    }

    
        // *** MODELS RELATIONS ***
        // ************************

        // ** COORDINATOR ** 

    /**
     * Get coordinator.
     */
    public function coordinator()
    {
        return $this->belongsTo('App\Models\Event\Person\EventCoordinator', 'event_coordinator_id');
    }

    /**
     * @return mixed
     */
    public function getCoordinatorCheckIdAttribute()
    {
        return isset($this->coordinator_id) ? $this->coordinator_id : null;
    }

    /**
     * @return mixed
     */
    public function getCoordinatorNameAttribute()
    {
        return isset($this->coordinator) ? $this->coordinator->full_name : '';
    }

        // ** VENUE ** 

    /**
     * Get venue.
     */
    public function venue()
    {
        return $this->belongsTo('App\Models\Event\Venue');
    }

    /**
     * @return string
     */
    public function getVenueNameAttribute()
    {
        return ($this->venue !== null) ? $this->venue->public_name : '';
    }

        // ** EVENT TYPE ** 

    /**
     * Get venue.
     */
    public function type()
    {
        return $this->belongsTo('App\Models\Event\EventType', 'event_type_id');
    }

    /**
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return ($this->type !== null) ? $this->type->name : '';
    }

        // ** EVENT HOST(S) ** 

    /**
     * The hosts of the event.
     */
    public function hosts()
    {
        return $this->belongsToMany('App\Models\Event\Person\EventHost', 'pivot_event_host', 'event_id', 'event_host_id');
    }

    /**
     * The host of the event.
     */
    public function host()
    {
        return isset($this->hosts) ? $this->hosts->first() : null;
    }

    /**
     * @return string
     */
    public function getHostNameAttribute()
    {
        return ($this->host() !== null) ? $this->host()->full_name : '';
    }

        // ** EVENT TOPICS ** 

    /**
     * The topics of the event.
     */
    public function topics()
    {
        return $this->belongsToMany('App\Models\Event\EventTopic', 'pivot_event_topic', 'event_id', 'event_topic_id');
    }

    /**
     * @return array
     */
    public function getTopicsCustomAttribute()
    {
        if (empty($this->topics)) {
            return [];
        }

        $topics = $this->topics->where('custom', 1);
        $resp = [];
        foreach ($topics as $topic) {
            $resp[$topic->id] = $topic->name;
        }

        return $resp;
    }

        // ** EVENT CATEGORIES ** 

    /**
     * The categories of the event.
     */
    public function categories()
    {
        return $this
        	->belongsToMany('App\Models\Event\VendorCategory', 'pivot_event_category', 'event_id', 'vendor_category_id')
        	->withPivot('slots');
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
        foreach ($this->categories as $catg) {
            $resp[$catg->id] = $catg->name;
        }

        return $resp;
    }

    /**
     * @return array
     */
    public function getCategoriesSlotsAttribute()
    {
        if (empty($this->categories)) {
            return [];
        }

        $resp = [];
        foreach ($this->categories as $catg) {
            $resp[$catg->id] = $catg->pivot->slots;
        }

        return $resp;
    }

        // ** VENDORS ** 

    /**
     * The vendors of the event.
     */
    public function vendors()
    {
        return $this
            ->belongsToMany('App\Models\Event\Person\EventVendor', 'pivot_event_vendor', 'event_id', 'vendor_id')
            ->withPivot('vendor_category_id', 'confirmed', 'status')
            ->where('status', '<>', config('event.status.declined'));
    }

    /**
     * The vendors of the event.
     */
    public function all_vendors()
    {
        return $this
            ->belongsToMany('App\Models\Event\Person\EventVendor', 'pivot_event_vendor', 'event_id', 'vendor_id')
            ->withPivot('vendor_category_id', 'confirmed', 'status');
    }

    /**
     * @return array
     */
    public function getVendorsListAttribute()
    {
        if (empty($this->vendors)) {
            return [];
        }

        $resp = [];
        foreach ($this->vendors as $vendor) {
            $resp[$vendor->id] = $vendor->full_name;
        }

        return $resp;
    }

    /**
     * @param $catg - vendor category
     * @return array of objects
     */
    public function vendorsByCategory($catg)
    {
        return $this->vendors()
            ->wherePivot('vendor_category_id', $catg)
            ->get();
    }

    /**
     * @param $catg - vendor category
     * @return array
     */
    public function listVendorsByCategory($catg)
    {
        if (is_object($catg)) {
            if (!isset($catg->id)) {
                return [];
            }
            $catg = $catg->id;
        }

        $vendors = $this->vendorsByCategory($catg);
        if (empty($vendors)) {
            return [];
        }

        $resp = [];
        foreach ($vendors as $vendor) {
            $resp[$vendor->id] = $vendor->full_name;
        }

        return $resp;
    }

    /**
     * @param $catg - vendor category
     * @return array with more fields
     */
    public function getVendorsByCategory($catg)
    {
        if (is_object($catg)) {
            if (!isset($catg->id)) {
                return [];
            }
            $catg = $catg->id;
        }

        $vendors = $this->vendorsByCategory($catg);
        if (empty($vendors)) {
            return [];
        }

        $resp = [];
        foreach ($vendors as $vendor) {
            $resp[$vendor->id] = collect($vendor->toArray())
                ->merge($vendor['pivot'])
                ->forget(['pivot', 'created_at', 'updated_at'])
                ->put('full_name', $vendor->full_name)
                ->all();
        }

        return $resp;
    }

    /**
     * Get All data for categories in array
     * For Assign Vendor UI
     * @return array
     */
    public function getCategoriesAlldataAttribute()
    {
        if (empty($this->categories)) {
            return [];
        }

        $resp = [];
        foreach ($this->categories as $catg) {
            $vendors_in = $this->listVendorsByCategory($catg);
            $vendors_avail = collect(VendorCategory::find($catg->id)->vendors_list)
                ->diffKeys($vendors_in)
                ->all();
            $slots_free = ($catg->pivot->slots - count($vendors_in)) >= 0
                ? $catg->pivot->slots - count($vendors_in)
                : 0;
            $resp[$catg->id] = [ 
                'name' => $catg->name,
                'slots' => $catg->pivot->slots,
                'slots_free' => $slots_free,
                'vendors_in' => $vendors_in,
                'vendors_avail' => $vendors_avail,
            ];
        }

        return $resp;
    }

    /**
     * Return if vendor could be invited
     * @return array
     */
    public function can_invite_vendor($vendor)
    {
        if ($this->categories->isEmpty() || !($vendor instanceof EventVendor) 
            || $vendor->categories->isEmpty() || empty($this->coordinator)) {
            return false;
        }

        $common_ids = $this->categories
            ->pluck('id')
            ->intersect($vendor->categories->pluck('id'));

        if ($common_ids->isEmpty()) {
            return false;
        }

        $categories = $this->categories
            ->filter(function ($value, $key) use ($common_ids) {
                return $common_ids->contains($value->id);
            });

        foreach ($categories->all() as $catg) {
            $vendors_in = $this->listVendorsByCategory($catg);
            if ($catg->pivot->slots > count($vendors_in)) {
                return true;
            }
        }

        return false;
    }


        // ** COMPLETE MESSAGE ** 

    /**
     * Get feedback message
     */
    public function complete_message()
    {
        return $this->belongsTo('App\Models\Event\UserMessage', 'complete_message_id');
    }

    /**
     * Get complete message text
     * @return string
     */
    public function getCompleteTextAttribute()
    {
        if (empty($this->complete_message)) {
            return '';
        }

        return $this->complete_message->body;
    }

        // ** FEEDBACKS ** 

    /**
     * The feedbacks of the event.
     */
    public function feedbacks()
    {
        return $this
            ->belongsToMany('App\Models\Event\VendorFeedback', 'pivot_feedback_vendor', 'event_id', 'feedback_id')
            ->withPivot('vendor_id');
    }

    /**
     * Get array for view
     * @return array
     */
    public function getFeedbacksForViewAttribute()
    {
        if (empty($this->feedbacks)) {
            return [];
        }

        $resp = [];
        foreach ($this->feedbacks as $feedback) {
            $resp[] = [
                'rating' => $feedback->rating,
                'attendance' => $feedback->attendance,
                'comments' => $feedback->comments,
                'vendor' => EventVendor::find($feedback->pivot->vendor_id)->full_name,
            ];
        }

        return $resp;
    }

    /**
     * Get array for view
     * @return array
     */
    public static function all_coordinators()
    {
        return EventCoordinator::with('base')->get()
            ->reject(function ($value, $key) {
                if ($value->base->isEmpty()) {
                    return true;
                }
                $deleted = $value->base->first(function ($value, $key) {
                    return isset($value->deleted_at);
                });
                return isset($deleted);
            })
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->full_name];
            })
            ->all();
    }



    // HELPER

    // calc distance between two coord
    public static function distance(array $p1, array $p2)
    {
        try {
            $lat1 = $p1['lat'];
            $lat2 = $p2['lat'];
            $lon1 = $p1['lng'];
            $lon2 = $p2['lng'];
        }
        catch (Exception $e) {
            return null;
        }
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        $distance_miles = $km * 0.621371;

        return $distance_miles;
    }

    public function with_conditions()
    {
        $resp = HostCommitment::where('event_id', $this->id)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($resp)) {
            return false;
        }

        return (bool)$resp->with_conditions;
    }

    public function conditions_message()
    {
        $resp = HostCommitment::where('event_id', $this->id)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($resp) || !$resp->with_conditions) {
            return null;
        }

        return $resp->conditions_message;
    }

        // Notes model
        // ***********

    public function get_notes()
    {
        if (!isset($this->notes_id)) {
            return null;
        }

        $model = UserMessage::find($this->notes_id);

        return isset($model) ? $model->body : null;
    }

    public function set_notes($body)
    {
        $persona_id = null;
        if (isset(access()->user()->persona)) {
            $persona_id = access()->user()->persona->id;
        }

        switch ((bool)$this->get_notes()) {
            case true:
                $model = UserMessage::where('id', $this->notes_id)
                    ->update(['body' => $body, 'persona_id' => $persona_id]);
                break;
            
            case false:
                $model = UserMessage::create(['body' => $body, 'persona_id' => $persona_id]);
                $this->notes_id = $model->id;
                $this->save();
                break;
        }

        return new RespSuccess('Notes updated');
    }


}
