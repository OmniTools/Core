<?php

namespace OmniTools\Core\Persistence\Entity;

/**
 * @Entity
 * @Table(name="user")
 * @HasLifecycleCallbacks
 */
class User extends \OmniTools\Core\Persistence\AbstractEntity
{
    /**
     * @Column(length=255, unique=true)
     */
    protected $email;

    /**
     * @Column(length=60, nullable=true)
     */
    protected $password;

    /**
     * @Column(type="json")
     */
    protected $roles = [];

    /**
     *
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     *
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     *
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     *
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
