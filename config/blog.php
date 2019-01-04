<?php
return [
    /**
     * User model are used in Authentication
     */
    'userModel' => App\User::class,

    /**
     * Layout's will be used to
     */
    'layout' => [
        'show' => 'permit::layouts.metronic.admin',
        'create' => 'prototype::layouts.blog'
    ],
    'activityType' => [
        //keyword=>Full Namespace Model class
    ],
    'defaultPhoto' => '/storage/images/default.png',

];