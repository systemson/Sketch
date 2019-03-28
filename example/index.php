<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('AMBER_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

use Amber\Sketch\Sketch;
use Amber\Sketch\Template\Template;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

// Setup the filesystem
$path = getcwd() . DIRECTORY_SEPARATOR;
$local = new Local($path);
$filesystem = new Filesystem($local);

// Setup the template
$template = new Template('view.php');
$template->setLayout('layouts/layout.php');
$template->setVar('name', 'World');
$template->setVar('description', 'This is a sample page.');
$template->setVar('version', 'v0.5.0-beta');

// Load and boot the template
$sketch = new Sketch($filesystem, $template);
$sketch->setViewsFolder('views');
$sketch->setCacheFolder('tmp/cache/views');

// Show the output
echo $sketch->toHtml();
