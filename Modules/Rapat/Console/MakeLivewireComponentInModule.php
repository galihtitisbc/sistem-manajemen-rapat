<?php

namespace Modules\Rapat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\File;

class MakeLivewireComponentInModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:livewire-module {module} {name}';
    protected $description = 'Membuat Livewire Component di dalam Module';
    /**
     * The console command description.
     *
     * @var string
     */

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
        $module = $this->argument('module');
        $name = $this->argument('name');

        $modulePath = base_path("Modules/$module/Http/Livewire");
        $viewPath = base_path("Modules/$module/Resources/views/livewire");

        if (!File::exists($modulePath)) {
            File::makeDirectory($modulePath, 0755, true);
        }

        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        $componentClass = "<?php\n\nnamespace Modules\\$module\\Http\\Livewire;\n\nuse Livewire\Component;\n\nclass $name extends Component\n{\n    public function render()\n    {\n        return view('$module::livewire." . strtolower($name) . "');\n    }\n}\n";
        file_put_contents("$modulePath/$name.php", $componentClass);

        $componentBlade = "<div>\n    <h1>Livewire di Module $module</h1>\n</div>";
        file_put_contents("$viewPath/" . strtolower($name) . ".blade.php", $componentBlade);

        $this->info("Livewire component $name berhasil dibuat di module $module!");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
