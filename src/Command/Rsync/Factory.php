<?php

namespace Vcn\Exeg\Command\Rsync;

use Vcn\Exeg\Command;
use Vcn\Exeg\Command\Ssh\Server;
use Vcn\Exeg\Shell;

class Factory
{
    public static function local(string $src, string $dest, ?Params $params = null): Command
    {
        $args = self::initRsyncArgs($params);

        $args[] = $src;
        $args[] = $dest;

        return new Command('rsync', $args);
    }

    public static function localToSsh(string $src, Server $destServer, string $dest, ?Params $params = null): Command
    {
        $args = self::initRsyncArgs($params, $destServer);

        $args[] = $src;
        $args[] = "{$destServer->getHost()}:{$dest}";

        return new Command('rsync', $args);
    }

    public static function sshToLocal(Server $srcServer, string $src, string $dest, ?Params $params = null): Command
    {
        $args = self::initRsyncArgs($params, $srcServer);

        $args[] = "{$srcServer->getHost()}:{$src}";
        $args[] = $dest;

        return new Command('rsync', $args);
    }

    private static function initRsyncArgs(?Params $params = null, ?Server $sshServer = null): array
    {
        $params = $params ?? Params::create();

        $args = [];

        if ($params->isArchive()) {
            $args[] = '--archive';
        }

        if ($params->isChecksum()) {
            $args[] = '--checksum';
        }

        if ($params->isCompress()) {
            $args[] = '--compress';
        }

        if ($params->isDelete()) {
            $args[] = '--delete';
        }

        if ($params->isInplace()) {
            $args[] = '--inplace';
        }

        if ($sshServer !== null) {
            $rshArg = Shell::render(new Command('ssh', $sshServer->createSshOptionArgs()));
            $args[] = "--rsh={$rshArg}";
        }

        return $args;
    }
}
