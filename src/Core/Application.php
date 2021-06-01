<?php

namespace App\Core;

use App\Core\Request\Request;
use App\Core\Response\RedirectResponse;
use App\Core\Response\Response;
use App\Core\Response\ResponseInterface;
use Exception;
use ReflectionMethod;
use ReflectionParameter;

class Application {
    /**
     * @var ApplicationContext
     */
    private $_context;

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function dispatch(Request $request): ResponseInterface
    {
        $this->_context = new ApplicationContext($request);

        if($this->_context->bdd() == null) {
            return new Response($this->_context, "errors/500");
        }

        $uri = $request->server()->uri();

        $data = array_slice(explode('/', $uri), 1, 2);

        $data = ($data[0] == "") ? [] : $data;

        if (count($data) == 1) {
            $data[1] = "index";
        } elseif(count($data) == 0) {
            $data[0] = "dashboard";
            $data[1] = "index";
        } elseif(count($data) > 2) {
            return new Response($this->_context, "errors/404");
        }

        $params = explode("?", $data[1]);
        $data[1] = $params[0];

        if(count($params) > 1) {
            $args = array_reduce(explode("&", $params[1]), function($a, $v) {
                $arg = explode("=", $v);
                $a[$arg[0]] = $arg[1];
                return $a;
            }, []);
        } else {
            $args = [];
        }

        $controller_path = "\\App\\Controller\\" . ucfirst($data[0]) . "Controller";
        $action = $data[1];

        if(!class_exists($controller_path)) {
            return new Response($this->_context, "errors/404");
        }

        $controller = new $controller_path($this->_context);

        if(!$controller instanceof AbstractController) {
            throw new Exception("$controller_path must be extends of AbstractController");
        }

        if(!method_exists($controller, $action)) {
            return new Response($this->_context, "errors/404");
        }

        if(!$controller->routes_request_auth) {
            throw new Exception("$controller_path must be implement route definitions");
        }

        if(!array_key_exists($action, $controller->routes_request_auth)) {
            throw new Exception("$controller_path must be implement route definition for $action");
        }

        if($controller->routes_request_auth[$action] && !$this->getContext()->auth()->isAuth()) {
            if($this->_context->request()->cookie()->has($this->_context->getConfig()->getAll()["first_connection_cookiekey"]))
                $this->_context->request()->flash()->push("errors", "You must be logged.");
            return new RedirectResponse("/auth/login");
        } elseif(!$controller->routes_request_auth[$action] && $this->getContext()->auth()->isAuth()) {
            $this->_context->request()->flash()->push("errors", "You must be logout.");
            return new RedirectResponse("/dashboard/index");
        }

        try {
            $method_args = array_reduce((new ReflectionMethod($controller, $action))->getParameters(), function($a, $v) use ($args) {
                /**
                 * @var ReflectionParameter $v
                 */
                if(array_key_exists($v->name, $args)) {
                    $a[] = $args[$v->name];
                } else {
                    $a[] = null;
                }
                return $a;
            }, []);
        } catch (\ReflectionException $e) {
            die($e->getMessage());
        }

        return call_user_func_array([$controller, $action], $method_args);
    }

    public function getContext(): ApplicationContext
    {
        return $this->_context;
    }
}
