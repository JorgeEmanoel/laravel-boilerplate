<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default system actions
    |--------------------------------------------------------------------------
    |
    | This option will describe all the actions the system peform and the acl
    | system should be in the known to handle it.
    |
    */
    'general_actions' => [
        'list',
        'create',
        'read',
        'update',
        'delete',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default system levels
    |--------------------------------------------------------------------------
    |
    | All the available system levels should be placed here, so the modules can
    | use it.
    |
    */
    'enabled_levels' => [
        'global' => true,
        'owner' => true,
        'relationship' => true,
        'user_relationship' => true,
        'column' => true,
        'user' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Access levels translation
    |--------------------------------------------------------------------------
    |
    | All the access level translated, to be used when placed into any user
    | interface.
    |
    */
    'level_translation' => [
        'global' => 'All records',
        'owner' => 'All records user created',
        'relationship' => 'Through relation',
        'user_relationship' => 'Through user relation',
        'column' => 'Through some user data',
        'user' => 'Specifc user',
    ],

    /*
    |--------------------------------------------------------------------------
    | System Modules
    |--------------------------------------------------------------------------
    |
    | Here is where all the system's modules must be placed, with their access
    | control settings.
    |
    */
    'modules' => [
        // EXAMPLE
        'App\Product' => [
            'name' => 'products',
            'display_name' => 'Products',
            'admin_only' => false,
            'specific_actions' => [
                'activate',
                'deactivate',
                'export',
            ],
            'levels' => [
                'global' => true,
                'owner' => 'user_id',
                'relationship' => [
                    // ONCE ONE OF THIS RETURN TRUE, THE USER CAN PEFORM THE ACTION
                    [
                        'model' => 'App\Product',
                        'path' => [
                            'user',
                            'user_id'
                        ],
                        'expected_value' => 'id',
                        'contains' => false,
                    ],
                ],
                'user_relationship' => [
                    // ONCE ONE OF THIS RETURN TRUE, THE USER CAN PEFORM THE ACTION
                    [
                        'path' => [
                            'products',
                        ],
                        'expected_value' => '',
                        'contains' => true,
                    ],
                ],
                'column' => [
                    ['name', '==' , 'MOTO G7 Play'],
                ],
                'user' => false,
            ],
            'lock_actions' => [
                'update',
                'delete',
            ],
            'active' => true,
        ],
    ],


];
