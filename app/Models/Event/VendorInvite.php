<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event\Event;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;

class VendorInvite extends Model
{
	use Traits\UIDTrait;

	public static function clear_uids($vendor_id, $event_id)
	{
		self::where([
				['vendor_id', '=', $vendor_id],
				['event_id', '=', $event_id],
			])
			->delete();
	}

	public static function insert_uids($vendor_id, $event_id)
	{
		self::clear_uids($vendor_id, $event_id);

		$uid_confirm = self::uid_5d();
		$uid_decline = self::uid_5d();

		self::insert([
		    ['vendor_id' => $vendor_id, 'event_id' => $event_id, 'action' => config('event.action.vendor.confirm'), 'uid' => $uid_confirm],
		    ['vendor_id' => $vendor_id, 'event_id' => $event_id, 'action' => config('event.action.vendor.decline'), 'uid' => $uid_decline],
		]);

		return ['confirm' => $uid_confirm, 'decline' => $uid_decline];
	}

    public static function accept_invitation($uid)
    {
    	$query = self::where([
				['uid', '=', $uid],
				['action', '=', config('event.action.vendor.confirm')],
			])
			->get();

		if ($query->isEmpty()) {
			return new RespFailure('Code not found');
		}

		$data = $query->first();

		$vendors = Event::findOrFail($data->event_id)->all_vendors()->where('vendor_id', $data->vendor_id)->get();

		foreach ($vendors as $vendor) {
			$vendor->pivot->confirmed = 1;
			$vendor->pivot->status = config('event.vendor.status.confirmed');
			$vendor->pivot->save();
		}

		self::clear_uids($data->vendor_id, $data->event_id);

		return new RespSuccess;
    }

    public static function decline_invitation($uid)
    {
    	$query = self::where([
				['uid', '=', $uid],
				['action', '=', config('event.action.vendor.decline')],
			])
			->get();

		if ($query->isEmpty()) {
			return new RespFailure('Code not found');
		}

		$data = $query->first();

		$vendors = Event::findOrFail($data->event_id)->all_vendors()->where('vendor_id', $data->vendor_id)->get();

		foreach ($vendors as $vendor) {
			$vendor->pivot->confirmed = 0;
			$vendor->pivot->status = config('event.vendor.status.declined');
			$vendor->pivot->save();
		}

		self::clear_uids($data->vendor_id, $data->event_id);

		return new RespSuccess;
    }
}
