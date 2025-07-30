<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeServiceCommand extends Command
{

    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-service-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
    $path = app_path("Services/{$name}.php");
    
    if (file_exists($path)) {
        $this->error("Service {$name} already exists!");
        return;
    }
    
    $directory = dirname($path);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    $stub = "<?php\n\nnamespace App\\Services;\n\nclass {$name}\n{\n    //\n}\n";
    
    file_put_contents($path, $stub);
    
    $this->info("Service {$name} created successfully.");
    }
}
