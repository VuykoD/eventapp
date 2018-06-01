<?php

namespace App\Http\Controllers\Backend\Access\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Access\Settings\Vendor_category;

use Carbon\Carbon;

class SettingsVendorCategoryController extends Controller
{
     public function index(Request $request)
     {
        if ($request->id){
          $data = Vendor_category::where('id',$request->id)->delete();
          return redirect()->route('admin.access.settings_vendor_category.index')->withFlashSuccess(trans('alerts.backend.settings_vendor_category.deleted'));
        }
        $Vendor_category = Vendor_category::select('id','name','code','num_code')->get();
        return view('app.admin.vendor_category')->with(['Vendor_category'=>$Vendor_category]);
     } 

    public function create(Request $request)
    {
      $data = null;
      if ($request->id){$data = Vendor_category::where('id',$request->id)->get();}
        return view('app.admin.vendor-category-create') ->with(['data'=>$data]);
    }

     public function store(Request $request)
    {
      if ($request->input('id')==null){
        Vendor_category::insert([
            'name'     => $request->input('name'),
            'code'     => $request->input('code'),
            'num_code' => $request->input('codeNumber'),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
         $message= 'alerts.backend.settings_vendor_category.created';
       }else{
        Vendor_category::where('id', $request->input('id'))
          ->update([
            'name'     => $request->input('name'),
            'code'     => $request->input('code'),
            'num_code' => $request->input('codeNumber'),
            'updated_at'=>Carbon::now(),
        ]);
        $message= 'alerts.backend.settings_vendor_category.updated';
       }
         
       return redirect()->route('admin.access.settings_vendor_category.index')->withFlashSuccess(trans($message)); 
     }

}
