<?php

namespace App\Http\Controllers\Backend\Access\Settings;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Access\Settings\Event_type;

use Carbon\Carbon;

class SettingsController extends Controller
{
     public function index(Request $request)
     {
        if ($request->id){
          $data = Event_type::where('id',$request->id)->delete();
          return redirect()->route('admin.access.settings_event_type.index')->withFlashSuccess(trans('alerts.backend.settings_event_type.deleted'));
        }
        $Event_type = Event_type::select('id','name','code','group_size','duration','additional')->get();
        return view('app.admin.event_types') ->with(['Event_type'=>$Event_type]);
     } 

     public function create(Request $request)
    {   
    	$data = null;
    	if ($request->id){$data = Event_type::where('id',$request->id)->get();}
        return view('app.admin.event-type-create') ->with(['data'=>$data]);
    }

    public function store(Request $request)
    {

        if ($request->input('id')==null){
        Event_type::insert([
            'name'      => $request->input('name'),
            'code'      => $request->input('code'),
            'group_size'=> $request->input('group_size'),
            'duration'  => $request->input('duration'),
            'additional'=> $request->input('additional'),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
         $message= 'alerts.backend.settings_event_type.created';
       }else{
       	Event_type::where('id', $request->input('id'))
          ->update([
            'name'      => $request->input('name'),
            'code'      => $request->input('code'),
            'group_size'=> $request->input('group_size'),
            'duration'  => $request->input('duration'),
            'additional'=> $request->input('additional'),
            'updated_at'=> Carbon::now(),
        ]); 
        $message= 'alerts.backend.settings_event_type.updated';
       }
         
       return redirect()->route('admin.access.settings_event_type.index')->withFlashSuccess(trans($message)); 

     }   
}
