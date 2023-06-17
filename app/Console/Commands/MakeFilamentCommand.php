<?php

namespace App\Console\Commands;

use App\Traits\FIlament\Commands\{HasMakeFilamentModal, HasMakeFilamentPages};
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeFilamentCommand extends Command
{
    use HasMakeFilamentModal;
    use HasMakeFilamentPages;

    protected $signature = 'make:filament {model} {--modal}';

    protected $description = 'Command description';

    protected string $namespace = 'App\Http\Livewire\Filament';

    protected string $path = 'app/Http/Livewire/Filament';

    public function __construct(
        protected Filesystem $files
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        if (!$this->argument('model')) {
            $this->error('Model name is required');

            return;
        }

        $this->namespace = $this->getNamespace($this->argument('model'));
        $this->path      = $this->getPath($this->argument('model'));

        $this->option('modal')
            ? $this->makeModal()
            : $this->makePages();
    }

    public function makeFile(string $path, string $contents): void
    {
        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info($this->getPathToLog($path) . ' created successfully');
        } else {
            $this->warn($this->getPathToLog($path) . ' already exists');
        }
    }

    public function getPathToLog(string $path): string
    {
        if (str($path)->contains('Filament/')) {
            return str($path)->explode('Filament/')->last();
        }

        return str($path)->explode('filament/')->last();
    }

    public function makeDirectory(string $path): string
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    public function getStubContents($stub, array $stubVariables = []): string|array|bool
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }

        return $contents;

    }

    public function getSingularClassName(string $name): string
    {
        return ucwords(Pluralizer::singular($name)) . 'Resource';
    }

    public function getResourceSingularName(string $name): string
    {
        return str(Pluralizer::singular($name))->lower();
    }

    public function getResourcePluralName(string $name): string
    {
        return ucwords(Pluralizer::plural($name));
    }

    private function getNamespace(string $argument): string
    {
        return 'App\Http\Livewire\Filament\\' . ucwords(Pluralizer::singular($argument)) . 'Resource';
    }

    private function getPath(string $argument): string
    {
        return 'app/Http/Livewire/Filament/' . ucwords(Pluralizer::singular($argument)) . 'Resource';
    }

}
