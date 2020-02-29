<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Admin\Api\User;

class Controller extends \OmniTools\Core\Api\AbstractController
{
    /**
     *
     */
    public function createActionUsingPost(
        \Doctrine\ORM\EntityManagerInterface $entityManager
    )
    {
        $userRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\User::class);
        $user = new \OmniTools\Core\Persistence\Entity\User([
            'email' => $this->getPayload('email'),
            'password' => password_hash($this->getPayload('password'), PASSWORD_DEFAULT)
        ]);

        $entityManager->persist($user);
        $entityManager->flush();

        return [
            'id' => $user->getId(),
            'email' => $user->getEmail()
        ];
    }

    /**
     *
     */
    public function deleteActionUsingDelete(
        \Doctrine\ORM\EntityManagerInterface $entityManager
    )
    {
        $userRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\User::class);
        $user = $userRepository->find($this->getPayload('userId'));

        $entityManager->remove($user);
        $entityManager->flush();

        return [

        ];
    }

    /**
     *
     */
    public function listActionUsingGet(
        \Doctrine\ORM\EntityManagerInterface $entityManager
    )
    {
        $userRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\User::class);
        $result = $userRepository->findBy([]);

        $list = [];

        foreach ($result as $user) {

            $list[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ];
        }

        return $list;
    }
}
