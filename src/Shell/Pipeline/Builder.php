<?php

namespace Vcn\Exeg\Shell\Pipeline;

use Vcn\Exeg\Command as BaseCommand;
use Vcn\Exeg\Shell\Command as ShellCommand;
use Vcn\Exeg\Shell\Pipeline;

class Builder
{
    /**
     * @var ShellCommand[]
     */
    private $shellCommands = [];

    public function __construct(BaseCommand $command, ?string $stdin = null, ?string $stderr = null)
    {
        $this->add($command, $stdin, null, $stderr);
    }

    public function then(BaseCommand $command, ?string $stderr = null): self
    {
        $this->add($command, null, null, $stderr);

        return $this;
    }

    public function last(BaseCommand $command, ?string $stdout = null, ?string $stderr = null): Pipeline
    {
        $this->add($command, null, $stdout, $stderr);

        return new Pipeline($this->shellCommands);
    }

    private function add(BaseCommand $command, ?string $stdin, ?string $stdout, ?string $stderr)
    {
        $this->shellCommands[] = new ShellCommand($command, $stdin, $stdout, $stderr);
    }
}
