<?php
/**
 *
 */

namespace OmniTools\Core\Persistence;

abstract class AbstractRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *
     */
    public function __toString(): string
    {
        return get_class($this);
    }
}