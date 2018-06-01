<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

/*
 * These routes require no user to be logged in
 */
Route::group(['namespace' => 'Event'], function () {

    // view event for all
    Route::get('events/{uid}', 'EventController@showPublic')->name('event.view.public');

    // decline invite
    // Route::get('vendor/decline/{uid}', 'EventController@viewInvite')->name('vendor.view.invite');
    Route::get('vendor/decline/{uid}', 'EventController@declineVendor')->name('vendor.decline.invite');
    // accept invite
    // Route::get('vendor/accept/{uid}', 'EventController@viewInvite')->name('vendor.view.invite');
    Route::get('vendor/accept/{uid}', 'EventController@confirmVendor')->name('vendor.confirm.invite');

});

Route::group(['middleware' => 'guest'], function () {
    // login free for all
    Route::get('/', 'LoginController@showLoginForm')->name('login');
});

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    /*
     * All route names are prefixed with 'frontend.user.'
     */

    // Route::group(['namespace' => 'Event'], function () {

    //     // view event for all
    //     Route::get('events/{uid}', 'EventController@showPublic');

    // });

    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        //
        Route::get('/', 'FrontendController@index')->name('index');
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
    });

    /*
     * All route names are prefixed with 'frontend.'
     * URLs start with /dashboard
     * namespace \Frontend\Event
     */

    Route::group([
        'middleware' => 'access.routeNeedsPermission:view-events',
    ], function () {
        Route::group(['namespace' => 'Event', 'prefix' => 'dashboard'], function () {
            /*
             * For DataTables
             */
            Route::post('event/get', 'EventTableController')->name('event.get');

            /*
             * For DataTables
             */
            Route::post('event/radius', 'EventTableRadiusController')->name('radius-event.get');
            
            /*
             * Host create
             */
            Route::post('event/host', 'EventController@newHost')->name('event.host.new');

            /*
             * Vendor create
             */
            Route::post('event/vendor', 'EventController@newVendor')->name('event.vendor.new');

            /*
             * Venue create
             */
            Route::post('event/venue', 'EventController@newVenue')->name('event.venue.new');

            /*
             * View nearby events
             */
            Route::post('event/nearby', 'EventController@viewNearby')->name('event.view.nearby');

            /*
             * Organization create
             */
            Route::post('event/organization', 'EventController@newOrganization')->name('event.organization.new');

            /*
             * View templates
             */
            Route::get('templates', 'EventController@viewTpl')->name('view.tpl');

            /*
             * Update templates
             */
            Route::post('templates', 'EventController@updateTpl')->name('update.tpl');

            /*
             * Event CRUD
             */
            Route::resource('event', 'EventController');

            /*
             * Specific Event
             */
            Route::group(['prefix' => 'event/{event}'], function () {
                /*
                 * Vendors assign
                 */
                Route::post('vendors', 'EventController@updateVendors')->name('event.vendors.update');

                /*
                 * Vendors assign UI
                 */
                Route::get('vendors', 'EventController@showVendors')->name('event.vendors.show');

                /*
                 * Complete event
                 */
                Route::post('complete', 'EventController@complete')->name('event.complete.post');

                /*
                 *  Cancel event
                 */
                Route::get('cancel', 'EventController@cancel')->name('event.cancel');

                /*
                 *  Postpone event
                 */
                Route::get('postpone', 'EventController@postpone')->name('event.postpone');

                /*
                 *  Pending event
                 */
                Route::get('pending', 'EventController@pending')->name('event.pending');

                /*
                 *  Postpone event
                 */
                Route::get('invite', 'EventController@inviteVendor')->name('event.invite_vendor');

                /*
                 *  Edit notes view
                 */
                Route::get('notes', 'EventController@notes')->name('event.notes.show');

                /*
                 *  Post notes
                 */
                Route::post('notes', 'EventController@updateNotes')->name('event.notes.update');

                /*
                 *  Post notes
                 */
                Route::post('email_all', 'EventController@emailAll')->name('event.email_all');

                /*
                 *  Post notes
                 */
                Route::post('docs', 'EventController@uploadDocs')->name('event.docs.upload');

                /*
                 * Complete UI
                 */
                Route::match(['get', 'patch'], 'complete', 'EventController@showComplete')->name('event.complete');

            });
        }); 
    }); 

    /*
     * All route names are prefixed with 'frontend.'
     * URLs start with /dashboard
     * namespace \Frontend\Event
     */

    Route::group([
        'middleware' => 'access.routeNeedsPermission:host-event',
    ], function () {
        Route::group(['namespace' => 'Event'], function () {

            /*
             * Host confirm view
             */
            Route::get('host/confirm/{uid}', 'EventController@viewConfirmHost')->name('host.confirm.view');

            /*
             * Host confirm 
             */
            Route::post('host/confirm/{uid}', 'EventController@confirmHost')->name('host.confirm');

            /*
             * Host decline
             */
            Route::post('host/decline/{uid}', 'EventController@declineHost')->name('host.decline');

        }); 
    }); 

    /*
     * All route names are prefixed with 'frontend.'
     * URLs start with /dashboard
     * namespace \Frontend\Event
     */

    Route::group([
        'middleware' => 'access.routeNeedsPermission:update-profile',
    ], function () {
        Route::group(['namespace' => 'Event', 'prefix' => 'dashboard'], function () {
            /*
             *  Post avatar
             */
            Route::post('avatar/{user}', 'EventController@uploadAvatar')->name('avatar.upload');

            /*
             *  Post logo
             */
            Route::post('logo/{org}', 'EventController@uploadLogo')->name('logo.upload');
        }); 
    });
     

});


