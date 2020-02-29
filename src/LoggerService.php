<?php
/**
 *
 */

namespace OmniTools\Core;

class LoggerService
{
    protected $session;
    protected $em;

    /**
     *
     */
    public function __construct(\OmniTools\Core\Session $session, \Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->em = $entityManager;
    }

    /**
     *
     */
    public function log(\OmniTools\Core\Persistence\AbstractEntity $subject, $action, array $context = null)
    {
        // Fetch user
        $userRepository = $this->em->getRepository(\OmniTools\Core\Persistence\Entity\User::class);
        $user = $userRepository->find($this->session->getId());

        // Create log
        $log = new \OmniTools\Core\Persistence\Entity\Log([
            'user' => $user,
            'subjectClass' => get_class($subject),
            'subjectId' => $subject->getId(),
            'action' => $action,
            'context' => $context ?? []
        ]);

        $this->em->persist($log);
    }

    /**
     *
     */
    public function getHistory(\OmniTools\Core\Persistence\AbstractEntity $subject)
    {
        // Fetch logs
        $logsRepository = $this->em->getRepository(\OmniTools\Core\Persistence\Entity\Log::class);
        $result = $logsRepository->findBy([
            'subjectClass' => get_class($subject),
            'subjectId' => $subject->getId()
        ], [ 'created' => 'DESC' ]);

        return $result;
    }
}