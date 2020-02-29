<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Session\Controller\Login;

use Doctrine\ORM\EntityManagerInterface;

class Controller extends \OmniTools\Core\AbstractController
{
    protected $publicActions = [
        'ajaxSubmit'
    ];

    /**
     * @return string
     */
    public function getPath(): string
    {
        return __DIR__ . '/';
    }

    /**
     *
     */
    public function ajaxSubmitAction(
        EntityManagerInterface $entityManager,
        \OmniTools\Core\Session $session
    ): \OmniTools\Core\View\Response
    {
        if (empty($_POST['password'])) {
            throw new \OmniTools\Core\Exception\InputMissing('Post', 'password');
        }

        $userRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\User::class);

        $user = $userRepository->findOneBy([
            'email' => $_POST['email']
        ]);

        if (empty($user)) {
            throw new \OmniTools\Core\Exception\NotFound('User', $_POST['email']);
        }

        if (!$user->verifyPassword($_POST['password'])) {
            throw new \OmniTools\Core\Exception\AuthenticationFailed('Anmeldung fehlgeschlagen.');
        }

        $session->login($user);

        return new \OmniTools\Core\View\ResponseJson([
            'redirect' => $this->getUri('index', 'Dashboard', 'Dashboard')
        ]);
    }

    /**
     *
     */
    public function indexAction(
        \OmniTools\Core\Session $session
    ): \OmniTools\Core\View\Response
    {
        // d($session);

        if ($session->isLoggedIn()) {
            return new \OmniTools\Core\View\ResponseRedirect($this->getUri('index', 'Dashboard'));
        }

        return $this->render();
    }

    /**
     *
     */
    public function quitAction(
        \OmniTools\Core\Session $session
    ): \OmniTools\Core\View\Response
    {
        $session->logout();

        return new \OmniTools\Core\View\ResponseRedirect($this->getUri('index'));
    }
}
