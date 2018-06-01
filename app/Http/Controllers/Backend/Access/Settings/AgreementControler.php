<?php

namespace App\Http\Controllers\Backend\Access\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Access\Settings\Agreement;

use Carbon\Carbon;

class AgreementControler extends Controller
{
    public function index(Request $request)
     {
     	$Agreement = Agreement::select('id','name','body')->get();
         return view('app.event.agreement-edit')->with(['Agreement'=>$Agreement]);;
     } 

     public function store(Request $request)
    {
        $inputText = htmlentities($request->input('body'));
       	Agreement::where('id', "1")
          ->update([
            'body'    => $inputText,
            'updated_at'=> Carbon::now(),
        ]); 

         
       return redirect()->route('admin.access.agreements.index')->withFlashSuccess(trans('alerts.backend.agreements.updated')); 
     }   
}
