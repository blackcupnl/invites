<?php

namespace BlackCup\Invites\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class MakeInviteCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:invite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new invite';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'invite';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../resources/stubs/InviteStub.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('invites.namespace', $rootNamespace.'\Invites');
    }
}
