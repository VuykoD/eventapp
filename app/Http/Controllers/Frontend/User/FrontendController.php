<?php

namespace App\Http\Controllers\Frontend\User;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Class FrontendController.
 */
class FrontendController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (session()->get('exception_thrown')) {
            return view('app.admin.blank');
        }

    	if (access()->user()) {
    		return redirect()
    			->route('frontend.user.dashboard');
    	}
    	
        return redirect()
			->route('frontend.login');
    }
    
}
