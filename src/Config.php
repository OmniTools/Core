<?php
/**
 *
 */

namespace OmniTools\Core;

class Config
{
    protected $data;

    /**
     *
     */
    public function __construct()
    {
        $this->data = require CORE_DIR . 'config/localconfig.php';
    }

    /**
     *
     */
    public function get($configPath)
    {
        $keys = explode('.', $configPath);

        $act = $this->data;

        foreach ($keys as $key) {

            if (!isset($act[$key])) {
                return null;
            }

            $act = $act[$key];
        }

        return $act;
    }
}
