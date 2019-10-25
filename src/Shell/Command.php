<?php

namespace Vcn\Exeg\Shell;

use Vcn\Exeg\Command as BaseCommand;

class Command
{
    /**
     * @var BaseCommand
     */
    private $command;

    /**
     * @var null|string
     */
    private $stdin;

    /**
     * @var null|string
     */
    private $stdout;

    /**
     * @var null|string
     */
    private $stderr;

    public function __construct(BaseCommand $command, ?string $stdin = null, ?string $stdout = null, ?string $stderr = null)
    {
        $this->command = $command;
        $this->stdin   = $stdin;
        $this->stdout  = $stdout;
        $this->stderr  = $stderr;
    }

    /**
     * @return BaseCommand
     */
    public function getCommand(): BaseCommand
    {
        return $this->command;
    }

    /**
     * @return null|string
     */
    public function getStdin(): ?string
    {
        return $this->stdin;
    }

    /**
     * @return null|string
     */
    public function getStdout(): ?string
    {
        return $this->stdout;
    }

    /**
     * @return null|string
     */
    public function getStderr(): ?string
    {
        return $this->stderr;
    }

    public function withStdin(?string $stdin): self
    {
        return new self(
            $this->command,
            $stdin,
            $this->stdout,
            $this->stderr
        );
    }

    public function withStdout(?string $stdout): self
    {
        return new self(
            $this->command,
            $this->stdin,
            $stdout,
            $this->stderr
        );
    }

    public function withStderr(?string $stderr): self
    {
        return new self(
            $this->command,
            $this->stdin,
            $this->stdout,
            $stderr
        );
    }
}
