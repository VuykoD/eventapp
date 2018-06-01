<?php

namespace App\Http\Controllers\Backend\Access\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Access\Settings\Event_topic;

use Carbon\Carbon;

class SettingsEventCategoryController extends Controller
{

     public function index(Request $request)
     {
        if ($request->id){
          $data = Event_topic::where('id',$request->id)->delete();
          return redirect()->route('admin.access.settings_event_category.index')->withFlashSuccess(trans('alerts.backend.settings_event_category.deleted'));
        }
        $Event_category = Event_topic::select('id','name','code','custom')->get();
        return view('app.admin.event_category') ->with(['Event_category'=>$Event_category]);
     } 

     public function create(Request $request)
    {
    	$data = null;
    	if ($request->id){$data = Event_topic::where('id',$request->id)->get();}
        return view('app.admin.event-category-create') ->with(['data'=>$data]);
    }

    public function store(Request $request)
    {
      if ($request->input('id')==null){
        Event_topic::insert([
            'name'      => $request->input('name'),
            'code'      => $request->input('code'),
            'custom'    => $request->input('custom'),
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
         $message= 'alerts.backend.settings_event_category.created';
       }else{
       	Event_topic::where('id', $request->input('id'))
          ->update([
            'name'      => $request->input('name'),
            'code'      => $request->input('code'),
            'custom'    => $request->input('custom'),
            'updated_at'=> Carbon::now(),
        ]); 
        $message= 'alerts.backend.settings_event_category.updated';
       }
         
       return redirect()->route('admin.access.settings_event_category.index')->withFlashSuccess(trans($message)); 
     }   
}
