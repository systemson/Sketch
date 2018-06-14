<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';

use Amber\Sketch\Sketch;
use Amber\Sketch\Template\Template;

$view = 'view.php';
$layout = 'layout.php';
$data = [];

$config = [
    'basepath' => getcwd(),
    'folders'  => [
        'views'    => 'views',
        'layouts'  => 'layouts',
        'includes' => 'includes',
        'partials' => 'includes/partials',
        'cache'    => 'cache',
    ],
];

/* Se instancia el borrador */
$sketch = new Sketch($config);

/* Se compila la plantilla */
$sketch->design($view, $layout, $data);

/* Se muestra el diseño */
$sketch->draw();
