<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('AMBER_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

use Amber\Sketch\Sketch;

$view = 'view.php';
$layout = 'layout.php';
$data = [];

$env = [
    'basepath'     => getcwd().'/',
    'enviroment'   => 'dev',
    'folders'      => [
        'views'    => 'views',
        'layouts'  => 'views/layouts',
        'includes' => 'views/includes',
        'partials' => 'views/includes/partials',
        'cache'    => 'tmp/cache/views',
    ],
    'tags'         => [
        'if'       => '',
        'elseif'   => '',
        'else'     => '',
        'endif'    => '',
        'foreach'  => '',
        'endforeach'=> '',
        'while'    => '',
        'while'    => '',
    ],
];

/* Se instancia el borrador */
$sketch = new Sketch($env);

/* Se compila la plantilla */
$sketch->design($view, $layout, $data);

/* Se muestra el diseño */
$sketch->draw();
