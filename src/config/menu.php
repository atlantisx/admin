<?php return array(
    'sidebar' => array(
        'home' => array(
            'parent'        => null,
            'route'         => 'student/home',
            'description'   => 'Home',
            'class'         => 'active'
        ),
        'application' => array(
            'parent'        => null,
            'route'         => '#',
            'description'   => 'Home'
        ),
        'ependahuluan' => array(
            'parent'        => 'application',
            'route'         => 'application/advance',
            'description'   => 'EPendahuluan',
            'class'         => ''
        )
    )
);