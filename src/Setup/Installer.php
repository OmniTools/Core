<?php
/**
 *
 */

namespace OmniTools\Core\Setup;

use Composer\Script\Event;

class Installer
{
    /**
     *
     */
    public static function execute(Event $event)
    {
        $coreDir = dirname($event->getComposer()->getConfig()->get('vendor-dir')) . '/';

        /*
        mkdir($coreDir . 'public');
        mkdir($coreDir . 'public');
        die($coreDir);
        echo "RUN INSTALLER!";
        */
    }
}
