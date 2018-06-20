<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';

use Amber\Sketch\Sketch;

$view = 'view.php';
$layout = 'layout.php';
$data = [];

$config = [
    'basepath'     => getcwd().'/',
    'enviroment'   => 'dev',
    'folders'      => [
        'views'    => 'views',
        'layouts'  => 'views/layouts',
        'includes' => 'views/includes',
        'partials' => 'views/includes/partials',
        'cache'    => 'tmp/cache/views',
    ],
];

/* Se instancia el borrador */
$sketch = new Sketch($config);

/* Se compila la plantilla */
$sketch->design($view, $layout, $data);

/* Se muestra el diseño */
$sketch->draw();
