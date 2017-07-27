<?php 


return (object) [

    'user_status' => array(
        'logical_erasure' => -1,
        'inactive' => 0,
        'active' => 1,
        'pending_activation' => 2,
    ),

    'user_ambit' => array(
        'frontend' =>  [
            'require' => [
                'email' => Lang::get('auth.email_required'),
                'pass'=> Lang::get('auth.pass_required'),
            ],
            'table' => 'users',
            'auth_redir' => '/dashboard/'
        ],
        'backend'=> [
            'require' => [
                'email' => Lang::get('email_required'),
                'pass'=> Lang::get('pass_required'),
            ],
            'table' => 'administrators',
            'auth_redir' => '/backend/dashboard/'
        ]
    )

];

?>