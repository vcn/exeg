<?php

namespace Vcn\Exeg\Command\Rsync;

class Params
{
    /**
     * @var bool
     */
    private $archive;

    /**
     * @var bool
     */
    private $checksum;

    /**
     * @var bool
     */
    private $compress;

    /**
     * @var bool
     */
    private $delete;

    /**
     * @var bool
     */
    private $inplace;

    /**
     * @var bool
     */
    private $cvsExclude;

    /**
     * @var string[]
     */
    private $excludes;

    /**
     * @param bool     $archive
     * @param bool     $checksum
     * @param bool     $compress
     * @param bool     $delete
     * @param bool     $inplace
     * @param bool     $cvsExclude
     * @param string[] $excludes
     */
    private function __construct(
        bool $archive,
        bool $checksum,
        bool $compress,
        bool $delete,
        bool $inplace,
        bool $cvsExclude,
        array $excludes
    ) {
        $this->archive    = $archive;
        $this->checksum   = $checksum;
        $this->compress   = $compress;
        $this->delete     = $delete;
        $this->inplace    = $inplace;
        $this->cvsExclude = $cvsExclude;
        $this->excludes   = $excludes;
    }

    public function isArchive(): bool
    {
        return $this->archive;
    }


    public function isChecksum(): bool
    {
        return $this->checksum;
    }


    public function isCompress(): bool
    {
        return $this->compress;
    }


    public function isDelete(): bool
    {
        return $this->delete;
    }


    public function isInplace(): bool
    {
        return $this->inplace;
    }


    public function isCvsExclude(): bool
    {
        return $this->cvsExclude;
    }

    public function withArchive(bool $archive = true): self
    {
        return new self($archive, $this->checksum, $this->compress, $this->delete, $this->inplace, $this->cvsExclude, $this->excludes);
    }

    public function withChecksum(bool $checksum = true): self
    {
        return new self($this->archive, $checksum, $this->compress, $this->delete, $this->inplace, $this->cvsExclude, $this->excludes);
    }

    public function withCompress(bool $compress = true): self
    {
        return new self($this->archive, $this->checksum, $compress, $this->delete, $this->inplace, $this->cvsExclude, $this->excludes);
    }

    public function withDelete(bool $delete = true): self
    {
        return new self($this->archive, $this->checksum, $this->compress, $delete, $this->inplace, $this->cvsExclude, $this->excludes);
    }

    public function withInplace(bool $inplace = true): self
    {
        return new self($this->archive, $this->checksum, $this->compress, $this->delete, $inplace, $this->cvsExclude, $this->excludes);
    }

    public function withCvsExclude(bool $cvsExclude = true): self
    {
        return new self($this->archive, $this->checksum, $this->compress, $this->delete, $this->inplace, $cvsExclude, $this->excludes);
    }

    public function withExcludes(array $excludes, bool $append = true): self
    {
        if ($append) {
            $excludes = array_merge($this->excludes, $excludes);
        }

        return new self($this->archive, $this->checksum, $this->compress, $this->delete, $this->inplace, $this->cvsExclude, $excludes);
    }

    public static function create(): self
    {
        return new self(false, false, false, false, false, false, []);
    }
}
