# Exeg

Exeg is a PHP-library that provides an abstraction for commands. Exeg makes it possible to execute the same command in different environments, for example local (using symfony/process), or over SSH.

# Quickstart

```php
<?php

use Vcn\Exeg\Command;
use Vcn\Exeg\Shell;

// Create a command
$command = new Command('cat', ['/etc/hosts']);

// We now have a platform-agnostic representation of the command:
var_dump($command);
echo PHP_EOL;

// Command-instances are immutable. Instead of setters the class has with-methods which create copies of the current
// instance with the specified property changed. Let's demonstrate it by complete rebuilding the command:
$command = $command
    ->withCmd('env')
    ->withArgs([])
    ->withWorkDir('/')
    ->withEnv(['FOO' => 'BAR']);

// withArgs() and withEnv() have an optional second boolean parameter which you can use to append them instead of replacing them
$command = $command->withEnv(['BAR' => 'BAZ'], true);

// Exeg also provides a few utilities to use the commands. For example, let's render the command to a string which has
// been properly escaped, ready for usage in a shell
$shellString = Shell::render($command) . PHP_EOL;

passthru($shellString);
```

# Symfony

Exeg provides an adapter to symfony/process:

```php
<?php

use Vcn\Exeg\Command;
use Vcn\Exeg\Symfony\ExegSymfony;
use Vcn\Exeg\Symfony\ProcessBuffer;

// ExegSymfony is the adapter from Exeg to symfony/process
$exegSymfony = new ExegSymfony();

// This is the command we want to execute.
$command = new Command('cat', ['/etc/hosts']);

// The ExegSymfony-instance can build a Symfony-process for us
$process = $exegSymfony->build($command);

// Symfony uses callbacks to handle process output. Exeg provides utilities to handle them in a few different ways. In
// this case, we want a callback that passes through all data to our own stdout/stderr.
$passthroughCallback = ProcessBuffer::passthrough();

// Run process and display exit code
$exitCode = $process->run($passthroughCallback);
echo "Command exited with code {$exitCode}" . PHP_EOL;
```

# Combining commands

Sometimes commands take commands as argument. You can use `Shell::render()` to generate the command argument.

```php
<?php

use Vcn\Exeg\Command;
use Vcn\Exeg\Shell;
use Vcn\Exeg\Symfony\ExegSymfony;
use Vcn\Exeg\Symfony\ProcessBuffer;

$exegSymfony = new ExegSymfony();

$catHostsCommand = new Command('cat', ['/etc/hosts']);
$sshCommand      = new Command('ssh', ['example.test', Shell::render($catHostsCommand)]);

$exegSymfony->run($sshCommand, ProcessBuffer::passthrough());
```