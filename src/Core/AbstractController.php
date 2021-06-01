<?php


namespace App\Core;


use App\Core\Response\RedirectResponse;
use App\Core\Response\Response;

abstract class AbstractController
{
    /**
     * @var null
     */
    public $routes_request_auth = null;

    /**
     * @var ApplicationContext
     */
    protected $_context;

    public function __construct(ApplicationContext $context)
    {
        $this->_context = $context;
    }

    /**
     * @param string $page_name
     * @param array $args
     * @return Response
     */
    protected function render(string $page_name, array $args = []): Response {
        return new Response($this->_context, $this->_context->route()->page($page_name), $args);
    }

    /**
     * @param string $route_name
     * @param array $params
     * @return RedirectResponse
     */
    protected function redirectTo(string $route_name, array $params = []) {
        return $this->_context->route()->redirectTo($route_name, $params);
    }
}
