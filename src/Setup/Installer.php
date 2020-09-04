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
    public static function postUpdate(Event $event)
    {
        $coreDir = dirname($event->getComposer()->getConfig()->get('vendor-dir')) . '/';
        $resDir = realpath(__DIR__ . '/../../resources/private/install') . '/';
        
        foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($resDir, \RecursiveDirectoryIterator::SKIP_DOTS),\RecursiveIteratorIterator::SELF_FIRST) as $item) {
            if ($item->isDir()) {
                
                if (!file_exists($coreDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) {
                    mkdir($coreDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());    
                }
                
            } else {
                copy($item, $coreDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }

        echo 'Installed default files.' . PHP_EOL;
    }
}
