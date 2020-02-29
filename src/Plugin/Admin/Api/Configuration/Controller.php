<?php
/**
 *
 */

namespace OmniTools\Core\Plugin\Admin\Api\Configuration;

class Controller extends \OmniTools\Core\Api\AbstractController
{
    /**
     *
     */
    public function localconfigActionUsingGet()
    {
        $path = CORE_DIR . 'config/localconfig.php';

        $config = require($path);

        return $config;
    }

    /**
     *
     */
    public function localconfigActionUsingPut()
    {
        $configfile = CORE_DIR . 'config/localconfig.php';

        // Check if file exists
        if (!file_exists($configfile)) {
            throw new \Exception('Config file does not exists.');
        }

        // Check if config file is writable
        if (!is_writable($configfile)) {
            throw new \Exception('Config file is not writable.');
        }

        // Parse config
        $config = json_decode($this->getPayloadRaw(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Payload syntax error.');
        }

        $src = '<?php ' . PHP_EOL . 'return ' . var_export($config, true) . ';' . PHP_EOL;

        file_put_contents($configfile, $src);

        return [];
    }
}
