<?php

namespace App\Http\Controllers\Backend\Access\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Access\Vendor\Vendor_invite;

use Carbon\Carbon;


class VendorPendingController extends Controller
{
    //
    public function index(Request $request)
     {

     	//dd(access()->user()->id);

		if (access()->user()->hasRoles('admin')){
        	$Vendor_invite = Vendor_invite::where('action', 'pending')->select('id','uid','action','vendor_id','event_id','invited_at','responsed_at')->get();
        }else{
        	$Vendor_invite = Vendor_invite::where('action', 'pending')->where('vendor_id', '5')->select('id','uid','action','vendor_id','event_id','invited_at','responsed_at')->get();
        }
        return view('app.admin.vendor_invite')->with(['Vendor_invite'=>$Vendor_invite]);
     } 
}