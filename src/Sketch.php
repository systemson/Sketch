<?php

namespace Amber\Sketch;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\File;
use Amber\Sketch\Template\TemplateInterface;

class Sketch
{
    protected $filesystem;

    protected $folders = [];

    protected $paths = [];
    protected $files = [];
    protected $content;

    protected $template;

    public function __construct(FilesystemInterface $filesystem, TemplateInterface $template = null)
    {
        $this->filesystem = $filesystem;
        $this->template = $template;
    }

    public function setFilesystem(FilesystemInterface $filesystem): void
    {
        $this->filesystem = $filesystem;   
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;   
    }

    public function setFolder(string $name, string $path): void
    {
        if (!$this->getFilesystem()->has($path)) {
            throw new \Exception("Error Processing Request", 1);
        }
        $this->folders[$name] = $path;
    }

    public function getFolder(string $name): string
    {
        return $this->folders[$name];
    }

    public function setViewsFolder(string $path): void
    {
        $this->setFolder('views', $path);
    }

    public function setCacheFolder(string $path): void
    {
        $this->setFolder('cache', $path);
    }

    public function setTemplate(TemplateInterface $template): void
    {
        $this->template = $template;
    }

    public function getTemplate(): TemplateInterface
    {
        return $this->template;
    }

    public function getTemplateFiles(): array
    {
        return $this->getTemplate()->files();
    }

    public function getFileContent(string $name): string
    {
        return $this->getFile($name)->read();
    }

    public function getFile(string $name): File
    {
        return $this->files[$name];
    }

    public function mountTemplate(): void
    {
        $template = $this->getTemplate();
        $baseFolder = $this->getFolder('views');

        foreach ($this->getTemplateFiles() as $name => $file) {
            $path = $baseFolder . DIRECTORY_SEPARATOR . $file;
            if (!$this->getFilesystem()->has($path)) {
                throw new \Exception("File {$path} does not exists");
            }

            $this->files[$name] = new File($this->getFilesystem(),$path);
        }
    }

    public function loadContent(): void
    {
        if ($this->getTemplate()->hasLayout()) {
            $content = $this->getFileContent('layout');
            $content = str_replace('<sketch>', $this->getFileContent('view'), $content);
        } else {
            $content = $this->getFileContent('view');
        }

        $this->content = $this->pushIncludes($content);
    }

    public function pushIncludes(string $content): string
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($content);

        $baseFolder = $this->getFolder('views');

        foreach ($dom->getElementsByTagName('include') as $include) {
            $path = $baseFolder . DIRECTORY_SEPARATOR . $include->getAttribute('src');
            $included = $this->getFilesystem()->read($path);
            $content = preg_replace("/<include[^>]+\>/i", $included, $content, 1);
        }

        return $content;
    }

    public function writeCacheFile(): void
    {
        $view = $this->getFile('view');

        $baseFolder = $this->getFolder('cache');
        $name = base64_encode($view->getPath());

        $fullname = $baseFolder . DIRECTORY_SEPARATOR . $name;

        $this->getFilesystem()->put($fullname, $this->content);
    }

    public function loadCacheFile(): string
    {
        $view = $this->getFile('view');

        $baseFolder = $this->getFolder('cache');
        $name = base64_encode($view->getPath());

        $fullname = $baseFolder . DIRECTORY_SEPARATOR . $name;

        ob_start();

        extract($this->getTemplate()->getVars());

        include $fullname;

        return ob_get_clean();
    }

    public function toHtml(): string
    {
        $this->mountTemplate();
        $this->loadContent();
        $this->writeCacheFile();

        return $this->loadCacheFile();
    }
}
