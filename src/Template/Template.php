<?php

namespace Amber\Sketch\Template;

class Template implements TemplateInterface
{
    protected $view;
    protected $layout;
    protected $includes = [];
    protected $vars = [];

    protected $tags = [
        'foreach' => ['<?php foreach((array) %s): ?>', '<?php endforeach; ?>'],
        'for' => ['<?php for(%s): ?>', '<?php endfor; ?>'],
        'while' => ['<?php while(%s): ?>', '<?php endwhile; ?>'],
        'if' => ['<?php if(%s): ?>', '<?php endif; ?>'],
        'else_if' => ['<?php elseif(%s): ?>'],
        'else' => ['<?php else: ?>'],
        'echo' => ['<?= $_helpers->e->__invoke(', '); ?>'],
        'var' => ['<?= $_helpers->e->__invoke($%s); ?>'],
        'raw' => ['<?= ', '; ?>'],
        'php' => ['<?php ', ' ;?>'],
    ];

    public $helpers = [];

    public function __construct(string $path = '', array $vars = [])
    {
        $this->setView($path);
        $this->setVars($vars);
        $this->setVar('_view', $this);
        $this->helpers = (object) [
            'e' => function ($value) {
                return htmlspecialchars($value);
            },

        ];
    }

    public function setView(string $path): self
    {
        $this->view = $path;
        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setLayout(string $path): self
    {
        $this->layout = $path;
        return $this;
    }

    public function hasLayout(): bool
    {
        return !is_null($this->layout);
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setInclude(string $name, string $path): self
    {
        $this->includes[$name] = $path;
        return $this;
    }

    public function getInclude(string $name): string
    {
        return $this->includes[$name];
    }

    public function setIncludes(array $includes): self
    {
        foreach ($includes as $name => $path) {
            $this->setInclude($name, $path);
        }
        return $this;
    }

    public function getIncludes(): array
    {
        return $this->includes;
    }

    public function setVar(string $name, $value): self
    {
        $this->vars[$name] = $value;
        return $this;
    }

    public function getVar(string $name)
    {
        return htmlentities($this->vars[$name]);
    }

    public function setVars(array $vars): self
    {
        foreach ($vars as $name => $value) {
            $this->setVar($name, $value);
        }
        return $this;
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
}
