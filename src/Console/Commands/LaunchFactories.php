<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class FactoryStarterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'launch:factories 
                            {path? : Path to the Models Directory. Default is App Path}
                            {--step : Step through the list of models to be created one at a time.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Combs the list of Models and creates factories for those that are missing.';

    protected $path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->path = ($this->argument('path')) ?: app_path();
        $models = collect(File::files($this->path))->values();

        $filesToIgnore = config('factory.ignore');

        $models = $models
            ->map(fn($file) => Str::remove(".php", $file->getFileName()))
            ->filter(fn($fileName) => ! Arr::has($filesToIgnore, $fileName));

        if($this->option('step')) {
            $models->each(function($fileName) {
                if( $this->confirm("Do you want to create a Factory for the {$fileName} Model?", true)){
                    $this->makeFactory($fileName);
                }
            });
        } else {
            $models->each(function($fileName) {
                $this->makeFactory($fileName);
            });
        }   

        return Command::SUCCESS;
    }

    protected function makeFactory($fileName)
    {
        Artisan::call('make:factory ' . $fileName);
        $this->info($fileName . Artisan::output());
    }
}
