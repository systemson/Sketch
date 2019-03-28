<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('AMBER_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

use Amber\Sketch\Sketch;
use Amber\Sketch\Template\Template;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;


$path = getcwd() . DIRECTORY_SEPARATOR;
$local = new Local($path);
$filesystem = new Filesystem($local);

$sketch = new Sketch($filesystem);
$sketch->setViewsFolder($path . 'views');
$sketch->setCacheFolder($path . 'tmp/cache/views');

$template = new Template();
$sketch->setTemplate($template);

/* Se muestra el diseño */
echo $sketch->toHtml();
