<?php

namespace Vcn\Exeg;

final class Command
{
    /**
     * @var string
     */
    private $cmd;

    /**
     * @var string[]
     */
    private $args;

    /**
     * @var null|string[]
     */
    private $env = [];

    /**
     * @var null|string
     */
    private $workDir;

    /**
     * @param string        $cmd
     * @param string[]      $args
     * @param null|string[] $env
     * @param string|null   $workDir
     */
    public function __construct(
        string $cmd,
        array $args = [],
        ?array $env = null,
        ?string $workDir = null
    ) {
        $this->cmd     = $cmd;
        $this->args    = $args;
        $this->env     = $env;
        $this->workDir = $workDir;
    }

    /**
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmd;
    }

    /**
     * @return string[]
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return null|string[]
     */
    public function getEnv(): ?array
    {
        return $this->env;
    }

    /**
     * @return null|string
     */
    public function getWorkDir(): ?string
    {
        return $this->workDir;
    }

    public function withCmd(string $cmd): self
    {
        return new self($cmd, $this->args, $this->env, $this->workDir);
    }

    public function withArgs(array $args, bool $append = false): self
    {
        if ($append) {
            $args = array_merge($this->args, $args);
        }

        return new self($this->cmd, $args, $this->env, $this->workDir);
    }

    public function withEnv(?array $env, bool $append = false): self
    {
        if ($this->env !== null && $append) {
            $env = array_merge($this->env, $env ?? []);
        }

        return new self($this->cmd, $this->args, $env, $this->workDir);
    }

    public function withWorkDir(?string $workDir): self
    {
        return new self($this->cmd, $this->args, $this->env, $workDir);
    }
}
