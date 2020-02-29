<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Session\Controller\Profile;

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
    public function ajaxUpdatePasswordAction(
        EntityManagerInterface $entityManager,
        \OmniTools\Core\Http\Post $post,
        \OmniTools\Core\Session $session
    )
    {
        // Validate required input
        $post->require([ 'pw1', 'pw2' ]);

        if ($post->get('pw1') != $post->get('pw2')) {
            throw new \Exception('Die Passwörter stimmen nicht überein.');
        }

        // Fetch user
        $userRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\User::class);
        $user = $userRepository->find($session->getId());

        $user->setPassword($post->get('pw1'));

        $entityManager->flush();

        return new \OmniTools\Core\View\ResponseJson();
    }

    /**
     *
     */
    public function indexAction(
        \OmniTools\Core\Session $session
    ): \OmniTools\Core\View\Response
    {


        return $this->render();
    }
}
