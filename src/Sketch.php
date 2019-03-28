<?php

namespace Amber\Sketch;

use League\Flysystem\FilesystemInterface;
use Amber\Sketch\Template\TemplateInterface;

class Sketch
{
    protected $filesystem;

    protected $paths = [];

    protected $template;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function setViewsFolder(string $path): void
    {
        if (!file_exists($path)) {
            throw new \Exception("Error Processing Request", 1);
        }
        $this->paths['views'] = $path;
    }

    public function setCacheFolder(string $path): void
    {
        if (!file_exists($path)) {
            throw new \Exception("Error Processing Request", 1);
        }
        $this->paths['cache'] = $path;
    }

    public function setTemplate(TemplateInterface $template)
    {
        $this->template = $template;
    }

    public function getTemplate(): TemplateInterface
    {
        return $this->template;
    }

    public function toHtml(): string
    {
        return $this->getTemplate()->output();
    }
}
