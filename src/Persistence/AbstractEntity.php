<?php
/**
 *
 */

namespace OmniTools\Core\Persistence;

/**
 * Class AbstractEntity
 * @package OmniTools\Core\Persistence
 */
abstract class AbstractEntity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     *
     */
    public function __construct(array $record = null)
    {
        if (!empty($record)) {
            foreach ($record as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     *
     */
    public function __toString(): string
    {
        return get_class($this) . ' #' . $this->getId();
    }

    /**
     * 
     */
    public function getClassName(): string
    {
        return get_class($this);
    }

    /**
     *
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     *
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime;
    }

    /**
     * @PrePersist
     */
    public function prePersist()
    {
        $now = new \DateTime;
        $this->created = $now;
        $this->updated = $now;
    }

    /**
     *
     */
    public function setFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
