<?php

namespace Vcn\Exeg\Shell;

use Vcn\Exeg\Command as BaseCommand;
use Vcn\Exeg\Shell;

class Pipeline
{
    /**
     * @var Command[]
     */
    private $commands = [];

    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public static function first(BaseCommand $command, ?string $stdin = null, ?string $stderr = null): Pipeline\Builder
    {
        return new Pipeline\Builder($command, $stdin, $stderr);
    }

    /**
     * @return Command[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function render()
    {
        return implode(
            ' | ',
            array_map(
                function (Command $command): string {
                    return '( ' . Shell::renderShellCommand($command) . ' )';
                },
                $this->commands
            )
        );
    }
}
