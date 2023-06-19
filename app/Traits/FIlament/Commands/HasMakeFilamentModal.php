<?php

namespace App\Traits\FIlament\Commands;

trait HasMakeFilamentModal
{
    public function makeModal(): void
    {
        $this->makeModalView();
        $this->makeModalFile();
    }

    /**
     * MODAL VIEW
     */
    public function makeModalView(): void
    {
        $path = $this->getSourceModalViewPath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceModalViewFile();

        $this->makeFile($path, $contents);
    }

    public function getSourceModalViewPath(): string
    {
        return base_path('resources/views/livewire/filament/' . str($this->argument('model'))->kebab()->toString() . '-resource' . '/index.blade.php');
    }

    public function getSourceModalViewFile(): string|array|bool
    {
        return $this->getStubContents($this->getModalViewStubPath(), $this->getModalViewStubVariables());
    }

    public function getModalViewStubPath(): string
    {
        return __DIR__ . '/../../../Console/stubs/filament/views/index.stub';
    }

    public function getModalViewStubVariables(): array
    {
        return [];
    }

    /**
     * MODAL PHP
     */
    public function makeModalFile(): void
    {
        $path = $this->getSourceModalPath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceModalFile();

        $this->makeFile($path, $contents);
    }

    public function getSourceModalPath(): string
    {
        return base_path($this->path) . '/Index.php';
    }

    public function getSourceModalFile(): string|array|bool
    {
        return $this->getStubContents($this->getModalStubPath(), $this->getModalStubVariables());
    }

    public function getModalStubPath(): string
    {
        return __DIR__ . '/../../../Console/stubs/filament/modal.stub';
    }

    public function getModalStubVariables(): array
    {
        return [
            'CLASS_NAME'             => 'Index',
            'NAMESPACE'              => $this->namespace,
            'MODEL_NAME'             => $this->argument('model'),
            'VIEW_PATH'              => $this->getModalViewPath($this->argument('model'), 'index'),
            'RESOURCE_PLURAL_NAME'   => $this->getResourcePluralName($this->argument('model')),
            'RESOURCE_SINGULAR_NAME' => $this->getResourceSingularName($this->argument('model')),
        ];
    }

    public function getModalViewPath(string $name, string $view): string
    {
        // livewire.{resourceName}-resource.index
        return 'livewire.filament.' . str($name)->kebab()->toString() . '-resource' . '.' . $view;
    }

}
