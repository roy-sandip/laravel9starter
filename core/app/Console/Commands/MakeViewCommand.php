<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {view} {--copy=} {--r|resource} {--f|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blade template.';

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
     * @return mixed
     */
    public function handle()
    {
        $resource = $this->option('resource');
        $force = $this->option('force');

        $view = $this->argument('view');
        $views = $this->views($view, $resource);
        




        $template = $this->getTemplate($this->option('copy'));
        
        $viewPath = $this->getViewPath();
        $this->createDir($viewPath.'/'.$views[0]);
        foreach($views as $singleView)
        {
            $path = $viewPath.'/'.$singleView;
            if (!$force && File::exists($path))
            {
                $this->error("View {$singleView} already exists!");
                continue;
            }
            File::put($path, $template);
            $this->info("View {$singleView} created.");
        }



    }

     /**
     * Get the views.
     *
     * @param string $view
     *
     * @return string
     */
    public function views($view, $resource = false)
    {
        if($resource)
        {
            $dir = str_replace('.', '/', $view);
            return [
                $dir.'/'.'index.blade.php',
                $dir.'/'.'create.blade.php',
                $dir.'/'.'edit.blade.php',
                $dir.'/'.'show.blade.php',
            ];
        }

        return [str_replace('.', '/', $view) . '.blade.php'];
    }



    private function getViewPath()
    {
        $paths = app('view.finder')->getPaths();
    
        if (count($paths) === 1) {
            return head($paths);
        }

        return $this->choice('Where do you want to create the view(s)?', $paths, head($paths));
    }

    /**
     * Get Template
     * */
    private function getTemplate($template)
    {
        if(empty($template))
        {
            return;
        }
        $template = $this->views($template);
        $path = $this->getViewPath().'/'. $template[0];

        if(!File::exists($path))
        {
            $this->error("Template does not exist!");
            exit;
        }
        return File::get($path);
    }

    /**
     * Create view directory if not exists.
     *
     * @param $path
     */
    public function createDir($path)
    {
        $dir = dirname($path);

        if (!file_exists($dir))
        {
            mkdir($dir, 0777, true);
        }
    }

}