<?php

namespace Vcn\Exeg\Symfony;

class ProcessLineBuffer
{
    /**
     * @var ProcessBuffer
     */
    private $processBuffer;

    /**
     * @var string
     */
    private $stdoutBuffer;

    /**
     * @var string
     */
    private $stderrBuffer;

    /**
     * @var callable
     */
    private $stdoutLineHandler;

    /**
     * @var callable
     */
    private $stderrLineHandler;

    public function __construct(callable $stdoutLineHandler, callable $stderrLineHandler)
    {
        $this->stdoutLineHandler = $stdoutLineHandler;
        $this->stderrLineHandler = $stderrLineHandler;

        $this->stdoutBuffer  = '';
        $this->stderrBuffer  = '';
        $this->processBuffer = new ProcessBuffer(
            function (string $data): void {
                $this->stdoutBuffer .= $data;
                $this->triggerStdOutLines();
            },
            function (string $data): void {
                $this->stderrBuffer .= $data;
                $this->triggerStdErrLines();
            }
        );
    }

    public function __invoke(string $type, string $bytes)
    {
        ($this->processBuffer)($type, $bytes);
    }

    public function flushBuffers(): void
    {
        $this->triggerStdOutLines();
        $this->triggerStdErrLines();

        if (!empty($this->stdoutBuffer)) {
            ($this->stdoutLineHandler)($this->stdoutBuffer);
        }

        if (!empty($this->stderrBuffer)) {
            ($this->stderrLineHandler)($this->stderrBuffer);
        }

        $this->stdoutBuffer = '';
        $this->stderrBuffer = '';
    }

    protected function triggerStdOutLines(): void
    {
        while (($eolPosition = strpos($this->stdoutBuffer, PHP_EOL)) > -1) {
            ($this->stdoutLineHandler)(substr($this->stdoutBuffer, 0, $eolPosition));
            $this->stdoutBuffer = substr($this->stdoutBuffer, $eolPosition + 1);
        }
    }

    protected function triggerStdErrLines(): void
    {
        while (($eolPosition = strpos($this->stderrBuffer, PHP_EOL)) > -1) {
            ($this->stderrLineHandler)(substr($this->stderrBuffer, 0, $eolPosition));
            $this->stderrBuffer = substr($this->stderrBuffer, $eolPosition + 1);
        }
    }
}
