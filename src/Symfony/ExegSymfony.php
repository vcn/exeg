<?php

namespace Vcn\Exeg\Symfony;

use RuntimeException;
use Symfony\Component\Process\Process;
use Vcn\Exeg\Command;

class ExegSymfony
{
    /**
     * @var null|callable
     */
    private $defaultCallback = null;

    /**
     * @var null|int
     */
    private $defaultTimeout = null;

    public function __construct(?callable $defaultCallback = null, ?int $defaultTimeout = null)
    {
        if (!class_exists(Process::class)) {
            throw new RuntimeException(sprintf("Please install symfony/process to make use of %s", self::class));
        }

        $this->defaultCallback = $defaultCallback;
        $this->defaultTimeout  = $defaultTimeout;
    }

    public function setDefaultCallback(?callable $callback): self
    {
        $this->defaultCallback = $callback;

        return $this;
    }

    public function setDefaultTimeout(?int $defaultTimeout): self
    {
        $this->defaultTimeout = $defaultTimeout;

        return $this;
    }

    /**
     * @param Command $command
     *
     * @return Process
     */
    public function build(Command $command): Process
    {
        $workDir = $command->getWorkDir();
        $env     = $command->getEnv();

        $process = new Process(array_merge([$command->getCmd()], $command->getArgs()));

        if ($workDir !== null) {
            $process->setWorkingDirectory($workDir);
        }

        if ($env !== null) {
            $process->setEnv($env);
        }

        if ($this->defaultTimeout !== null) {
            $process->setTimeout($this->defaultTimeout);
        }

        return $process;
    }

    /**
     * @param Command       $command
     * @param null|callable $callback
     *
     * @return Process
     */
    public function start(Command $command, ?callable $callback = null): Process
    {
        $process = $this->build($command);
        $process->start($callback ?? $this->defaultCallback);

        return $process;
    }

    /**
     * @param Command       $command
     * @param null|callable $callback
     *
     * @return Process
     */
    public function run(Command $command, ?callable $callback = null): Process
    {
        $process = $this->build($command);
        $process->run($callback ?? $this->defaultCallback);

        return $process;
    }
}
