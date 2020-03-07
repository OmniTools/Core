<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Utility\Controller\Logger;

use Doctrine\ORM\EntityManagerInterface;

class Controller extends \OmniTools\Core\AbstractController
{
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
    public function ajaxModalHistoryAction(
        \OmniTools\Core\View $view,
        \OmniTools\Core\Http\Get $get,
        EntityManagerInterface $entityManager,
        \OmniTools\Core\LoggerService $loggerService
    )
    {        
        // Fetch entity
        $repository = $entityManager->getRepository($get->get('entity'));
        $entity = $repository->find($get->get('entityId'));

        return new \OmniTools\Core\View\ResponseJson([
            'html' => $view->render('AjaxModalHistory.html.twig', [
                'history' => $loggerService->getHistory($entity)
            ])
        ]);
    }
}
