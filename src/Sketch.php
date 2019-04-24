<?php

namespace Amber\Sketch;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\File;
use Amber\Sketch\Template\TemplateInterface;
use Carbon\Carbon;
use Closure;

class Sketch
{
    protected $filesystem;

    protected $folders = [];

    protected $paths = [];
    protected $files = [];
    protected $content;

    protected $template;
    protected $tags = [];

    protected $locked = false;

    const PARENT_TAG = 'sketch';

    public function __construct(FilesystemInterface $filesystem, TemplateInterface $template = null)
    {
        $this->setFilesystem($filesystem);
        $this->setTemplate($template);
    }

    public function setFilesystem(FilesystemInterface $filesystem): void
    {
        $this->filesystem = $filesystem;
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }

    public function setTemplate(TemplateInterface $template = null): void
    {
        $this->template = $template;
    }

    public function getTemplate(): TemplateInterface
    {
        return $this->template;
    }

    public function setFolder(string $name, string $path): void
    {
        if (!$this->getFilesystem()->has($path)) {
        	$this->getFilesystem()->createDir($path);
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

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setTag(string $name, string $opening, string $closing = null): void
    {
        $this->tags[$name] = (object) [
            'opening' => $opening,
            'closing' => $closing,
        ];
    }

    public function getTag(string $name): Closure
    {
        return $this->tags[$name];
    }

    public function setTags(array $tags): void
    {
        foreach ($tags as $name => $replace) {
            $this->setTag($name, $replace[0], $replace[1] ?? null);
        }
    }

    public function getTags(array $tags): Closure
    {
        foreach ($tags as $name) {
            $return[$name] = $this->getTag($name);
        }
    }

    public function mountTemplate(): void
    {
        $template = $this->getTemplate();
        $this->setTags($template->getTags());
        $baseFolder = $this->getFolder('views');

        foreach ($this->getTemplateFiles() as $name => $file) {
            $path = $baseFolder . DIRECTORY_SEPARATOR . $file;
            if (!$this->getFilesystem()->has($path)) {
                throw new \Exception("File {$path} does not exists");
            }

            $this->files[$name] = new File($this->getFilesystem(), $path);
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

        $content = $this->pushIncludes($content);
        $this->content = $this->replaceTags($content);
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

    public function getCacheName()
    {
        $view = $this->getFile('view');
        return $this->getFolder('cache') . DIRECTORY_SEPARATOR . sha1($view->getPath());
    }

    public function getCacheFullName()
    {
        return $this->getFilesystem()->getAdapter()->getPathPrefix() . $this->getCacheName();
    }

    public function writeCacheFile(): void
    {
        $this->getFilesystem()->put($this->getCacheName(), $this->content);
    }

    public function replaceTags(string $content)
    {
        foreach ($this->tags as $tag => $replace) {
        	// Define the full tag name
            $name = static::PARENT_TAG . '-' . $tag;

            // Extract the tag arguments
            preg_match_all("/<{$name}(=\"(.*)\")?>/i", $content, $matches);
            $matches = $this->getTagsMatches($matches);

            foreach ($matches as $match) {
                // Replace the opening tag and push the arguments, if any.
                $content = str_replace($match->name, sprintf($replace->opening, trim($match->args)), $content);

                // Replace the closing tag
                $content = preg_replace("/<\/{$name}+.*?\>/i", $replace->closing, $content);
            }

        }

        return $content;
    }

    public function getTagsMatches($matches)
    {
    	$return = [];

    	for ($x=0; $x < count($matches[0]); $x++) { 
    		$return[] = (object) [
    			'name' => reset($matches)[$x],
    			'args' => end($matches)[$x],
    		];
    	}

    	return $return;
    }

    public function compile()
    {
        if ($this->isLocked()) {
            return;
        }

        if ($this->cacheExpired()) {
            $this->loadContent();
            $this->writeCacheFile();
        }
    }

    public function loadCacheFile(): string
    {
        ob_start();

        extract($this->getTemplate()->getVars());

        include $this->getCacheFullName();

        return ob_get_clean();
    }

    public function isLocked(): bool
    {
        return $this->locked;
    }

    public function lock(bool $lock = true): void
    {
        $this->locked = $lock;
    }

    public function cacheExpired(): bool
    {
        $filesystem = $this->getFilesystem();

        if ($filesystem->has($this->getCacheName())) {
            $cache = $filesystem->getTimestamp($this->getCacheName());
        } else {
            return true;
        }

        foreach ($this->getFiles() as $file) {
            if ($cache <= $file->getTimestamp()) {
                return true;
            }
        }
        return false;
    }

    public function toHtml(): string
    {
        $this->mountTemplate();

        $this->compile();

        return $this->loadCacheFile();
    }
}
