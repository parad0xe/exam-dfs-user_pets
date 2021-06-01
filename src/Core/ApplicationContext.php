<?php


namespace App\Core;

use App\Core\Request\Request;
use PDO;

class ApplicationContext
{
    /**
     * @var Auth
     */
    private $_auth;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var BDD
     */
    private $_bdd;

    /**
     * @var Configuration
     */
    private $_config;

    /**
     * @var Route
     */
    private $_route_map;

    public function __construct(Request $request)
    {
        $this->_config = new Configuration();
        $this->_auth = new Auth($request);
        $this->_request = $request;
        $this->_bdd = new BDD($this);
        $this->_route_map = new Route($this);
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return $this->_request;
    }

    /**
     * @return Auth
     */
    public function auth(): Auth
    {
        return $this->_auth;
    }

    /**
     * @return PDO|null
     */
    public function bdd()
    {
        return $this->_bdd->getPdo();
    }

    /**
     * @return Configuration
     */
    public function getConfig() {
        return $this->_config;
    }

    /**
     * @return Route
     */
    public function route() {
        return $this->_route_map;
    }
}
