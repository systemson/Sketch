<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('AMBER_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

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

// Load and boot the template
$sketch = new Sketch($filesystem, $template);
$sketch->setViewsFolder('views');
$sketch->setCacheFolder('tmp/cache/views');
$sketch->setGlobal('version', 'v0.2.0-dev');
$sketch->setTag('lap', '<?= number_format(microtime(true) - AMBER_START, 6); ?>');
$sketch->dev(true);

// Show the output
echo $sketch->toHtml();
