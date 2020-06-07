<?php
return [
    /**
     * User model are used in Authentication
     */
    'userModel' => App\Models\User::class,

    /**
     * Layout's will be used to
     */
    'layout' => [
        'show' => 'blog::layouts.app',
        'create' => 'blog::layouts.app'
    ],
    'activityType' => [
        //keyword=>Full Namespace Model class
    ],
    'defaultPhoto' => '/storage/images/default.png',

];
