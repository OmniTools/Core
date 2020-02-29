<?php
/**
 *
 */

namespace OmniTools\Core;

class Session
{
    protected $id = null;

    /**
     *
     */
    public function __construct()
    {
        session_name('omnitools');
        session_start();

        if (!empty($_SESSION['omnitools']['session']['id'])) {
            $this->id = $_SESSION['omnitools']['session']['id'];
        }
    }

    /**
     *
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     *
     */
    public function isLoggedIn(): bool
    {
        if ($this->getId() === null) {
            return false;
        }

        return true;
    }

    /**
     * Login user
     */
    public function login(\OmniTools\Core\Persistence\Entity\User $user): void
    {
        $this->id = $user->getId();
        $_SESSION['omnitools']['session']['id'] = $this->id;
    }

    /**
     *
     */
    public function logout(): void
    {
        $this->id = null;

        $_SESSION['omnitools']['session'] = [ ];
    }
}