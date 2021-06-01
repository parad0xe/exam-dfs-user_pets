<?php
namespace App\Core\Request;

use App\Core\Request\Bag\Cookie;
use App\Core\Request\Bag\Files;
use App\Core\Request\Bag\Flash;
use App\Core\Request\Bag\Get;
use App\Core\Request\Bag\Post;
use App\Core\Request\Bag\Server;
use App\Core\Request\Bag\Session;

class Request
{
    /**
     * @var Server
     */
    private $_server;

    /**
     * @var Post
     */
    private $_post;

    /**
     * @var Post
     */
    private $_get;

    /**
     * @var Session
     */
    private $_session;

    /**
     * @var Flash
     */
    private $_flash;

    /**
     * @var Files
     */
    private $_files;

    /**
     * @var Cookie
     */
    private $_cookie;

    public function __construct(array $server, array $post, array $get)
    {
        $this->_server = new Server($server);
        $this->_post = new Post($post);
        $this->_get = new Get($get);
        $this->_session = new Session($_SESSION);
        $this->_flash = new Flash($_SESSION);
        $this->_files = new Files($_FILES);
        $this->_cookie = new Cookie($_COOKIE);
    }

    /**
     * @return Post
     */
    public function post(): Post
    {
        return $this->_post;
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->_server;
    }

    /**
     * @return Get
     */
    public function get(): Get {
        return $this->_get;
    }

    /**
     * @return Session
     */
    public function session(): Session {
        return $this->_session;
    }

    /**
     * @return Flash
     */
    public function flash(): Flash
    {
        return $this->_flash;
    }

    /**
     * @return Files
     */
    public function files(): Files {
        return $this->_files;
    }

    /**
     * @return Cookie
     */
    public function cookie(): Cookie {
        return $this->_cookie;
    }
}
