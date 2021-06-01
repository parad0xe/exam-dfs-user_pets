<?php


namespace App\Core;

use stdClass;

class Configuration
{
    /**
     * @var array
     */
    private $_config;

    public function __construct()
    {
        $this->_config = require_once(__DIR__ . '/../../app_configuration.php');
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->_config["app_root_dir"];
    }

    /**
     * @return string
     */
    public function getPublicDir()
    {
        return $this->_config["app_public_dir"];
    }

    /**
     * @return string
     */
    public function getPagesDir()
    {
        return $this->_config["app_page_dir"];
    }

    /**
     * @return stdClass
     */
    public function getDatabaseConfig() {
        $o = new stdClass();

        foreach ($this->_config["database"] as $db_conf_key => $db_conf_value)
            $o->$db_conf_key = $db_conf_value;

        return $o;
    }

    /**
     * @return array
     */
    public function getAll() {
        return $this->_config;
    }
}
