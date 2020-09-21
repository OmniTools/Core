<?php

namespace OmniTools\Core\Persistence\Entity;

/**
 * @Entity
 * @Table(name="file")
 * @HasLifecycleCallbacks
 */
class File extends \OmniTools\Core\Persistence\AbstractEntity
{
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @Column(length=255, nullable=false)
     */
    protected $name;

    /**
     * @Column(length=255, nullable=true)
     */
    protected $path;

    /**
     * @Column(length=255, nullable=true)
     */
    protected $hashcode;

    /**
     *
     */
    public function __construct(array $record = null)
    {
        parent::__construct($record);
    }

    /**
     *
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     *
     */
    public function getSize(): int
    {
        return filesize(CORE_DIR . $this->getPath());
    }

    /**
     * @PostRemove
     */
    public function postRemove()
    {
        $path = CORE_DIR . $this->getPath();

        unlink($path);
    }

    /**
     *
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
