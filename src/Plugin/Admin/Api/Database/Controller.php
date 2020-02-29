<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Admin\Api\Database;

class Controller extends \OmniTools\Core\Api\AbstractController
{
    /**
     *
     */
    public function migrateActionUsingPost(
        \Doctrine\ORM\EntityManagerInterface $entityManager
    )
    {

       //d(CORE_DIR);
        $command = 'php ' . CORE_DIR . '/app/vendor/bin/doctrine-migrations diff';

        $output = '';

        echo exec( $command, $output, $return_var );

        d($output);
    }
}
