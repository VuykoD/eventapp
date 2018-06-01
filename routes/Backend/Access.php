<?php

/**
 * All route names are prefixed with 'admin.access'.
 */


Route::group([
    'prefix'     => 'access',
    'as'         => 'access.',
    'namespace'  => 'Access',
], function () {

    /*
     * User Management
     */
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-users',
    ], function () {
        Route::group(['namespace' => 'User'], function () {
            /*
             * For DataTables
             */
            Route::post('user/get', 'UserTableController')->name('user.get');

            /*
             * User Status'
             */
            Route::get('user/deactivated', 'UserStatusController@getDeactivated')->name('user.deactivated');
            Route::get('user/deleted', 'UserStatusController@getDeleted')->name('user.deleted');

            /*
             * User CRUD
             */
            Route::resource('user', 'UserController');

            /*
             * Specific User
             */
            Route::group(['prefix' => 'user/{user}'], function () {
                // Account
                Route::get('account/confirm/resend', 'UserConfirmationController@sendConfirmationEmail')->name('user.account.confirm.resend');

                // Status
                Route::get('mark/{status}', 'UserStatusController@mark')->name('user.mark')->where(['status' => '[0,1]']);

                // Password
                Route::get('password/change', 'UserPasswordController@edit')->name('user.change-password');
                Route::patch('password/change', 'UserPasswordController@update')->name('user.change-password');

                // Access
                Route::get('login-as', 'UserAccessController@loginAs')->name('user.login-as');
            });

            /*
             * Deleted User
             */
            Route::group(['prefix' => 'user/{deletedUser}'], function () {
                Route::get('delete', 'UserStatusController@delete')->name('user.delete-permanently');
                Route::get('restore', 'UserStatusController@restore')->name('user.restore');
            });

            /*
            * Vendor Users
            */
            Route::get('user/vendor/index', 'UserController@showVendors')->name('vendor.index');
            Route::post('user/vendor/get', 'VendorTableController')->name('vendor.get');
            Route::patch('user/vendor/{user}/approve', 'UserController@approveVendor')->name('vendor.approve');
            Route::patch('user/vendor/{user}/deny', 'UserController@denyVendor')->name('vendor.deny');
        });
    });

    /*
     * Role Management
     */
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-roles',
    ], function () {
        Route::group(['namespace' => 'Role'], function () {
            Route::resource('role', 'RoleController', ['except' => ['show']]);

            //For DataTables
            Route::post('role/get', 'RoleTableController')->name('role.get');
        });
    });

    /*
     * Settings
     */
  Route::group([
        'middleware' => 'access.routeNeedsPermission:settings',
    ], function () {
        Route::group(['namespace' => 'Settings'], function () {
           
            Route::resource('settings_event_type'     , 'SettingsController'              );
            Route::resource('settings_event_category' , 'SettingsEventCategoryController' );
            Route::resource('settings_vendor_category', 'SettingsVendorCategoryController');
            Route::resource('agreements', 'AgreementControler');

            //For DataTables
             //Route::post('settings_event_type/get', 'SettingsController@data');
        });
    });

      /*
     * vendor
     */
  Route::group([
        'middleware' => 'access.routeNeedsPermission:vendor',
    ], function () {
        Route::group(['namespace' => 'Vendor'], function () {

            Route::resource('vendor_confirmed'     , 'VendorConfirmedController'              );
            Route::resource('vendor_pending'     , 'VendorPendingController'              );

        });
    });

    /*
     * Event Related Objects Management
     */
    Route::group([
        'middleware' => 'access.routeNeedsPermission:manage-all-events', 'namespace' => 'Event', 'prefix' => 'event'
    ], function () {
        Route::group(['namespace' => 'Organization'], function () {
            Route::resource('org', 'OrganizationController');

            //For DataTables
            Route::post('org/get', 'OrganizationTableController')->name('org.get');
        });
    });
});
