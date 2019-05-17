<?php

namespace Vcn\Exeg\Command\Ssh;

use Vcn\Exeg\Command;
use Vcn\Exeg\Shell;

class Server
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var null|int
     */
    private $port;

    /**
     * @var null|string
     */
    private $user;

    /**
     * @var null|string
     */
    private $identityFile;

    /**
     * @var null|string
     */
    private $knownHostsFile;

    /**
     * @param string      $host
     * @param null|int    $port
     * @param null|string $user
     * @param null|string $identityFile
     * @param null|string $knownHostsFile
     */
    public function __construct(string $host, ?int $port = null, ?string $user = null, ?string $identityFile = null, ?string $knownHostsFile = null)
    {
        $this->host           = $host;
        $this->user           = $user;
        $this->port           = $port;
        $this->identityFile   = $identityFile;
        $this->knownHostsFile = $knownHostsFile;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return null|int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return null|string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getIdentityFile(): ?string
    {
        return $this->identityFile;
    }

    /**
     * @return null|string
     */
    public function getKnownHostsFile(): ?string
    {
        return $this->knownHostsFile;
    }

    public function command(string $command, array $args = [], ?array $env = null, ?string $workDir = null): Command
    {
        return $this->adopt(new Command($command, $args, $env, $workDir));
    }

    /**
     * Returns a ssh-command with all connection arguments set, but without remote command.
     */
    public function baseCommand(): Command
    {
        return new Command('ssh', array_merge($this->createSshOptionArgs(), [$this->host]));
    }

    /**
     * Creates a new Command executing the provided Command on the ssh server.
     */
    public function adopt(Command $command): Command
    {
        return $this->baseCommand()->withArgs([Shell::render($command)], true);
    }

    public function createSshOptionArgs(): array
    {
        $args = [];

        if ($this->port !== null) {
            array_push($args, '-o', "Port={$this->port}");
        }

        if ($this->user !== null) {
            array_push($args, '-o', "User={$this->user}");
        }

        if ($this->identityFile !== null) {
            array_push($args, '-o', "IdentityFile={$this->identityFile}");
        }

        if ($this->knownHostsFile !== null) {
            array_push($args, '-o', "UserKnownHostsFile={$this->knownHostsFile}");
        }

        return $args;
    }
}
