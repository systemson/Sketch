<?php

require __DIR__.'\..\vendor\autoload.php';

use Amber\Sketch\Sketch;
use Amber\Sketch\Template\Template;

$view = 'view.php';
$layout = 'layout.php';
$data = [];

$config = [
    'basepath' => getcwd(),
    'folders' => [
        'views' => 'views',
        'layouts' => 'layouts',
        'includes' => 'includes',
        'partials' =>  'includes/partials',
        'cache' =>  'cache',
    ],
];

/* Se crea la plantilla */
$template = new Template($view, $layout, $data);

//$template->setView($view);
//$template->setLayout($layout);
//$template->setData(['data' => 'data']);
var_dump($template);
/* Se instancia el borrador */
$sketch = new Sketch($config);

/* Se compila la plantilla */
$sketch->design($template, ['lol' => 'lol', 'lal' => 'lal']);

/* Se muestra el diseño */
$sketch->draw();
