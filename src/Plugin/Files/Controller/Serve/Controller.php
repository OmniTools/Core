<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Files\Controller\Serve;

use Doctrine\ORM\EntityManagerInterface;
use OmniTools\Core\Http\Get;

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
    public function downloadAction(
        Get $get,
        EntityManagerInterface $entityManager
    ): \OmniTools\Core\View\Response
    {
        // Fetch file
        $filesRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\File::class);
        $file = $filesRepository->find($get->get('fileId'));

        http_response_code(200);

        header('Content-Type: application/pdf');
        header('Content-disposition: attachment; filename="' . ($file->getName()) . '"');

        readfile(CORE_DIR . $file->getPath());
        exit;
    }
}
