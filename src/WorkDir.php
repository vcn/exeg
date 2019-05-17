<?php

namespace Vcn\Exeg;

class WorkDir
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function command(string $command, array $args = [], ?array $env = null): Command
    {
        return new Command($command, $args, $env, $this->path);
    }

    /**
     * Creates a new Command executing the provided Command in this working directory. Alias for
     * `$command->withWorkDir($workDir->getPath())`.
     */
    public function adopt(Command $command): Command
    {
        return $command->withWorkDir($this->path);
    }
}
