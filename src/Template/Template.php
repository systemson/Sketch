<?php

namespace Amber\Sketch\Template;

class Template implements TemplateInterface
{
    protected $view;
    protected $layout;
    protected $includes = [];
    protected $vars = [];

    public function __construct(string $path, array $vars = [])
    {
        $this->setView($path);
        $this->setVars($vars);
    }

    public function setView(string $path): void
    {
        $this->view = $path;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setLayout(string $path): void
    {
        $this->layout = $path;
    }

    public function hasLayout(): bool
    {
        return !is_null($this->layout);
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setInclude(string $name, string $path): void
    {
        $this->includes[$name] = $path;
    }

    public function getInclude(string $name): string
    {
        return $this->includes[$name];
    }

    public function setIncludes(array $includes): void
    {
        foreach ($includes as $name => $path) {
            $this->setInclude($name, $path);
        }
    }

    public function getIncludes(): array
    {
        return $this->includes;
    }

    public function setVar(string $name, $value): void
    {
        $this->vars[$name] = $value;
    }

    public function getVar(string $name)
    {
        return htmlentities($this->vars[$name]);
    }

    public function setVars(array $vars): void
    {
        foreach ($vars as $name => $value) {
            $this->setVars($name, $value);
        }
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function files(): array
    {
        $files = [
            'view' => $this->getView(),
            'layout' => $this->getLayout(),
        ];

        foreach ($this->getIncludes() as $name => $path) {
            $files[$name] = $path;
        }

        return $files;
    }

    public function output()
    {
        return '<h1>Hello world.</h1>';
    }
}
