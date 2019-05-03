<?php

namespace Vcn\Exeg\Symfony;

use Symfony\Component\Process\Process;

class ProcessBuffer
{
    /**
     * @var callable
     */
    private $stdoutHandler;
    /**
     * @var callable
     */
    private $stderrHandler;

    /**
     * @param callable $stdoutHandler
     * @param callable $stderrHandler
     */
    public function __construct(callable $stdoutHandler, callable $stderrHandler)
    {
        $this->stdoutHandler = $stdoutHandler;
        $this->stderrHandler = $stderrHandler;
    }

    public function __invoke(string $type, string $bytes)
    {
        if (Process::ERR === $type) {
            ($this->stderrHandler)($bytes);
        } elseif (Process::OUT === $type) {
            ($this->stdoutHandler)($bytes);
        }
    }

    public static function passthrough($stdoutResource = null, $stderrResource = null): self
    {
        $stdoutResource = $stdoutResource ?? STDOUT;
        $stderrResource = $stderrResource ?? STDERR;

        return new self(
            function (string $data) use ($stdoutResource) {
                fwrite($stdoutResource, $data);
            },
            function (string $data) use ($stderrResource) {
                fwrite($stderrResource, $data);
            }
        );
    }
}
