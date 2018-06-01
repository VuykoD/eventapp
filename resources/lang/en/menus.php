<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menus Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in menu items throughout the system.
    | Regardless where it is placed, a menu item can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'title' => 'Access Management',

            'roles' => [
                'all'        => 'All Roles',
                'create'     => 'Create Role',
                'edit'       => 'Edit Role',
                'management' => 'Role Management',
                'main'       => 'Roles',
            ],

            'settings_event_type' => [
                'all'        => 'All Event Types',
                'create'     => 'Create Event Type',
                'edit'       => 'Edit Event Type',
                'management' => 'Event Type Management',
                'main'       => 'Event Types',
            ],

             'settings_event_category' => [
                'all'        => 'All Event Categories',
                'create'     => 'Create Event Category',
                'edit'       => 'Edit Event Category',
                'management' => 'Event Category Management',
                'main'       => 'Event Categories',
            ],

            'settings_vendor_category' => [
                'all'        => 'All Vendor Categories',
                'create'     => 'Create Vendor Category',
                'edit'       => 'Edit Vendor Category',
                'management' => 'Vendor Category Management',
                'main'       => 'Vendor Categories',
            ],

            'agreement' => [
                'all'        => 'All Agreements',
                'create'     => 'Create Term of Service Agreement',
                'edit'       => 'Edit Term of Service Agreement',
                'management' => 'Term of Service Agreement Management',
                'main'       => 'Term of Service Agreements',
            ],

            'vendor_invite' => [
                'all'        => 'All Confirmed Events',
                'management' => 'Vendor Invitation Management',
            ],

            'users' => [
                'all'             => 'Active Users',
                'change-password' => 'Change Password',
                'create'          => 'Create User',
                'deactivated'     => 'Deactivated Users',
                'deleted'         => 'Deleted Users',
                'edit'            => 'Edit User',
                'main'            => 'Users',
                'view'            => 'View User',
                'vendors'         => 'Vendors',
            ],
        ],

        'log-viewer' => [
            'main'      => 'Log Viewer',
            'dashboard' => 'Dashboard',
            'logs'      => 'Logs',
        ],

        'sidebar' => [
            'dashboard' => 'Dashboard',
            'general'   => 'General',
            'system'    => 'System',
        ],
    ],

    'language-picker' => [
        'language' => 'Language',
        /*
         * Add the new language to this array.
         * The key should have the same language code as the folder name.
         * The string should be: 'Language-name-in-your-own-language (Language-name-in-English)'.
         * Be sure to add the new language in alphabetical order.
         */
        'langs' => [
            'ar'    => 'Arabic',
            'da'    => 'Danish',
            'de'    => 'German',
            'el'    => 'Greek',
            'en'    => 'English',
            'es'    => 'Spanish',
            'fr'    => 'French',
            'id'    => 'Indonesian',
            'it'    => 'Italian',
            'nl'    => 'Dutch',
            'pt_BR' => 'Brazilian Portuguese',
            'ru'    => 'Russian',
            'sv'    => 'Swedish',
            'th'    => 'Thai',
        ],
    ],
];
