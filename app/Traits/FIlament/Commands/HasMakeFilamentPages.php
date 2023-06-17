<?php

namespace App\Traits\FIlament\Commands;

trait HasMakeFilamentPages
{
    public function makePages(): void
    {
        $this->makeIndexPage();
        $this->makeCreatePage();
        $this->makeEditPage();
    }

    public function makeIndexPage(): void
    {
        $this->makePageView('index');
        $this->makePageFile('Index');
    }

    public function makeCreatePage(): void
    {
        $this->makePageView('create');
        $this->makePageFile('Create');
    }

    public function makeEditPage(): void
    {
        $this->makePageView('edit');
        $this->makePageFile('Edit');
    }

    /**
     * MAKE VIEW
     */
    public function makePageView(string $view): void
    {
        $path = $this->getSourcePageViewPath($view);

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourcePageViewFile($view);

        $this->makeFile($path, $contents);
    }

    public function getSourcePageViewPath(string $view): string
    {
        return base_path("resources/views/livewire/filament/{$this->getResourceSingularName($this->argument('model'))}-resource/{$view}.blade.php");
    }

    public function getSourcePageViewFile(string $view): string|array|bool
    {
        return $this->getStubContents($this->getPageViewStubPath($view), $this->getPageViewStubVariables());
    }

    public function getPageViewStubPath(string $view): string
    {
        return __DIR__ . "/../../../Console/stubs/filament/views/{$view}.stub";
    }

    public function getPageViewStubVariables(): array
    {
        return [
            'LOWER_SINGULAR_NAME' => $this->getResourceSingularName($this->argument('model')),
        ];
    }

    /**
     * MAKE FILE
     */
    public function makePageFile(string $view): void
    {
        $path = $this->getSourcePageFilePath($view);

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourcePageFileFile($view);

        $this->makeFile($path, $contents);
    }

    public function getSourcePageFilePath(string $view): string
    {
        return base_path("$this->path/{$view}.php");
    }

    public function getSourcePageFileFile(string $view): string|array|bool
    {
        return $this->getStubContents($this->getPageFileStubPath($view), $this->getPageFileStubVariables($view));
    }

    public function getPageFileStubPath(string $view): string
    {
        $view = str($view)->lower()->toString();

        if ($view === 'index') {
            return __DIR__ . "/../../../Console/stubs/filament/pages.stub";
        }

        return __DIR__ . "/../../../Console/stubs/filament/pages/{$view}.stub";
    }

    public function getPageFileStubVariables(string $view): array
    {
        return [
            'CLASS_NAME'             => $view,
            'NAMESPACE'              => $this->namespace,
            'MODEL_NAME'             => $this->argument('model'),
            'VIEW_PATH'              => $this->getModalViewPath($this->argument('model')),
            'RESOURCE_PLURAL_NAME'   => $this->getResourcePluralName($this->argument('model')),
            'RESOURCE_SINGULAR_NAME' => $this->getResourceSingularName($this->argument('model')),
        ];
    }

}
