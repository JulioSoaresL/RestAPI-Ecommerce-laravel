<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

#[Signature('make:trait {name : O nome da Trait}')]
#[Description('Cria uma nova Trait PHP no diretório app/Traits')]
class MakeTraitCommand extends Command
{
    public function handle(Filesystem $files): int
    {
        $name = Str::studly(basename(str_replace('\\', '/', $this->argument('name'))));

        $directory = app_path('Traits');
        $filePath = $directory . '/' . $name . '.php';

        if (! $files->isDirectory($directory)) {
            $files->makeDirectory($directory, 0755, true);
        }

        if ($files->exists($filePath)) {
            $this->error("A Trait {$name} já existe em: {$filePath}");
            return self::FAILURE;
        }

        $content = <<<PHP
<?php

namespace App\Traits;

trait {$name}
{
    // Adicione a lógica da sua trait aqui
}
PHP;

        $files->put($filePath, $content);

        $this->info("Trait criada com sucesso em: app/Traits/{$name}.php");

        return self::SUCCESS;
    }
}
