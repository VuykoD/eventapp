<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event\Event;
use App\Exceptions\GeneralException;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;

class HostInvite extends Model
{
    use Traits\UIDTrait;

	public static function clear_uids($host_id, $event_id)
	{
		self::where([
				['host_id', '=', $host_id],
				['event_id', '=', $event_id],
			])
			->delete();
	}

	public static function insert_uids($host_id, $event_id)
	{
		self::clear_uids($host_id, $event_id);

		$uid_confirm = self::uid_5d();

		self::insert([
		    ['host_id' => $host_id, 'event_id' => $event_id, 'action' => config('event.action.host.confirm'), 'uid' => $uid_confirm],
		]);

		return ['confirm' => $uid_confirm];
	}

	public static function check_invitation($uid)
    {
    	$query = self::where([
				['uid', '=', $uid],
				['action', '=', config('event.action.host.confirm')],
			])
			->get();

		if ($query->isEmpty()) {
			throw new GeneralException('Code not found');
		}

		$data = $query->first();
		$event = Event::findOrFail($data->event_id);

		return new RespSuccess('Found', ['event' => $event]);
    }

    public static function accept_invitation($uid)
    {
    	$query = self::where([
				['uid', '=', $uid],
				['action', '=', config('event.action.host.confirm')],
			])
			->get();

		if ($query->isEmpty()) {
			return new RespFailure('Code not found');
		}

		$data = $query->first();
		$event = Event::findOrFail($data->event_id);
		$event->status = config('event.status.confirmed');
		$event->save();

		self::clear_uids($data->host_id, $data->event_id);

		return new RespSuccess('Success', ['event' => $event, 'host_id' => $data->host_id]);
    }

    public static function decline_invitation($uid)
    {
    	$query = self::where([
				['uid', '=', $uid],
				['action', '=', config('event.action.host.confirm')],
			])
			->get();

		if ($query->isEmpty()) {
			return new RespFailure('Code not found');
		}

		$data = $query->first();
		$event = Event::findOrFail($data->event_id);
		$event->status = config('event.status.declined');
		$event->save();

		self::clear_uids($data->host_id, $data->event_id);

		return new RespSuccess('Success', ['event' => $event, 'host_id' => $data->host_id]);
    }
}
