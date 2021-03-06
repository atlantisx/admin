<?php return array(

    //
    // Specify routing for Rest & Rpc
    //

    'router' => array(
        'routes' => array(

            'users_rpc_user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => 'users',
                    'defaults' => array(
                        'controller' => 'Modules\\Users\\Api\\V1\\Rpc\\User\\UserController',
                        'action' => 'change-email',
                    ),
                ),
            ),

            'users_rest_user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => 'users',
                    'defaults' => array(
                        'controller' => 'Modules\\Users\\Api\\V1\\Rest\\User\\UserController',
                    ),
                ),
            ),

        ),
    ),


    //
    // RPC mount point
    //

    'rpc' => array(

        /**
         * Users
         */
        'Modules\\Users\\Api\\V1\\Rpc\\User\\UserController' => array(
            'route_name' => 'users_rpc_user',
            'service_name' => 'Users'
        ),

    ),


    //
    // REST mount point collection
    //

    'rest' => array(

        /**
         * Users
         */
        'Modules\\Users\\Api\\V1\\Rest\\User\\UserController' => array(
            'listener' => 'Users\\V1\\Rest\\User\\UserResource',
            'route_name' => 'users_rest_user',
            'route_identifier_name' => 'id',
            'collection_name' => 'users',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Modules\\Users\\Api\\V1\\Rest\\User\\UserEntity',
            'collection_class' => 'Modules\\Users\\Api\\V1\\Rest\\User\\UserCollection',
            'service_name' => 'Users',
        ),

    ),
);
