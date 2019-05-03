<?php

namespace Vcn\Exeg;

class Shell
{
    public static function render(
        Command $command,
        ?string $stdinFile = null,
        ?string $stdoutFile = null,
        ?string $stderrFile = null
    ): string {
        $shellCommand = '';

        $cmd     = $command->getCmd();
        $args    = $command->getArgs();
        $workDir = $command->getWorkDir();
        $env     = $command->getEnv();

        if ($workDir !== null) {
            $shellCommand .= sprintf('cd %s && ', escapeshellarg($workDir));
        }

        foreach (($env ?? []) as $name => $value) {
            $shellCommand .= sprintf("%s=%s ", $name, escapeshellarg($value));
        }

        // The actual command
        $escapedCmd  = escapeshellcmd($cmd);
        $escapedArgs = array_map('escapeshellarg', $args);

        $shellCommand .= sprintf('%s %s', $escapedCmd, implode(' ', $escapedArgs));

        if ($stdinFile !== null) {
            $shellCommand .= sprintf(' < %s', escapeshellarg($stdinFile));
        }

        if ($stdoutFile !== null) {
            $shellCommand .= sprintf(' 1> %s', escapeshellarg($stdoutFile));
        }

        if ($stderrFile !== null) {
            $shellCommand .= sprintf(' 2> %s', escapeshellarg($stderrFile));
        }

        return $shellCommand;
    }
}
