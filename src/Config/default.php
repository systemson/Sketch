<?php

return [

    'basepath' => getcwd().DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR,

    'cache' =>  getcwd().DIRECTORY_SEPARATOR.'tmp'.
    DIRECTORY_SEPARATOR.'cache'.
    DIRECTORY_SEPARATOR.'views'.
    DIRECTORY_SEPARATOR,

    'folders' => [
        'views' => '',
        'layouts' => 'layouts',
        'includes' => 'includes',
        'partials' =>  'includes\partials',
    ],

];
