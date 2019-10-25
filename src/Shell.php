<?php

namespace Vcn\Exeg;

class Shell
{
    public static function renderShellCommand(Shell\Command $shellCommand): string
    {
        $renderedCommand = '';

        $command = $shellCommand->getCommand();
        $stdin   = $shellCommand->getStdin();
        $stdout  = $shellCommand->getStdout();
        $stderr  = $shellCommand->getStderr();

        $cmd     = $command->getCmd();
        $args    = $command->getArgs();
        $workDir = $command->getWorkDir();
        $env     = $command->getEnv();

        if ($workDir !== null) {
            $renderedCommand .= sprintf('cd %s && ', escapeshellarg($workDir));
        }

        foreach (($env ?? []) as $name => $value) {
            $renderedCommand .= sprintf("%s=%s ", $name, escapeshellarg($value));
        }

        $renderedCommand .= escapeshellcmd($cmd);

        if (!empty($args)) {
            $renderedCommand .= ' ' . implode(' ', array_map('escapeshellarg', $args));
        }

        if ($stdin !== null) {
            $renderedCommand .= sprintf(' < %s', escapeshellarg($stdin));
        }

        if ($stdout !== null) {
            $renderedCommand .= sprintf(' 1> %s', escapeshellarg($stdout));
        }

        if ($stderr !== null) {
            $renderedCommand .= sprintf(' 2> %s', escapeshellarg($stderr));
        }

        return $renderedCommand;
    }

    /**
     * @param Command     $command
     * @param null|string $stdinFile
     * @param null|string $stdoutFile
     * @param null|string $stderrFile
     *
     * @return string
     */
    public static function render(
        Command $command,
        ?string $stdinFile = null,
        ?string $stdoutFile = null,
        ?string $stderrFile = null
    ): string {
        return self::renderShellCommand(new Shell\Command($command, $stdinFile, $stdoutFile, $stderrFile));
    }
}
