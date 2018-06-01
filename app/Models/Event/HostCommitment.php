<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralMail;

class HostCommitment extends Model
{
    protected $fillable = ['host_id', 'event_id', 'ipadr', 'with_conditions', 'conditions_message', 'committed_at'];

    public static function add_response($resp, $request)
    {
    	$model = new self();
    	$model->fill($request->all());
    	$model->action = $resp->action;
    	$model->event_id = $resp->event->id;
    	$model->host_id = $resp->host_id;
    	$model->ipadr = Request::ip();
    	$model->save();

    	if (!empty($resp->event->coordinator)) {
    		return;
    	}

    	$event = $resp->event;

    	$visit_url = route('frontend.event.show', $event->id);
    	switch ($resp->action) {
    		case config('event.action.host.confirm'):
    			$notify_message = tpl_repo('event.email.template.coordinator.host_commit');
    			if (isset($model->with_conditions)) {
    				$notify_message .= ' Host submitted conditions: ' . $model->conditions_message;
    			}
    			break;

    		case config('event.action.host.decline'):
    			$notify_message = tpl_repo('event.email.template.coordinator.host_declined');
    			break;
    		
    		default:
    			$notify_message = '';
    			break;
    	}
        Notification::send($event->coordinator, new GeneralMail($notify_message, $visit_url));
    }
}
