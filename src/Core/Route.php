<?php


namespace App\Core;


use App\Core\Response\RedirectResponse;
use stdClass;

class Route
{
    private $_route_map = [];

    public function __construct(ApplicationContext $context)
    {
        $this->__load($context);
    }

    /**
     * @param string $route_name
     * @return stdClass|null
     */
    public function get(string $route_name) {
        if(array_key_exists($route_name, $this->_route_map)) {
            return $this->_route_map[$route_name];
        }

        return null;
    }

    /**
     * @param string $route_name
     * @param array $params
     * @return string|null
     */
    public function url(string $route_name, array $params = []) {
        if(($route = $this->get($route_name)))
            return $route->url . $this->__urlParamsEncode($params);

        return null;
    }

    /**
     * @param string $route_name
     * @return string|null
     */
    public function page(string $route_name) {
        if(($route = $this->get($route_name))) {
            return $route->page;
        }

        return null;
    }

    /**
     * @param string $uri
     * @param array $params
     * @return RedirectResponse
     */
    public function redirect(string $uri, array $params = []): RedirectResponse {
        return new RedirectResponse($uri . $this->__urlParamsEncode($params));
    }

    /**
     * @param string $route_name
     * @param array $params
     * @return RedirectResponse
     */
    public function redirectTo(string $route_name, array $params = []): RedirectResponse {
        return new RedirectResponse($this->url($route_name, $params));
    }

    /**
     * Map all the routes accessible from the controllers
     * @param ApplicationContext $context
     */
    private function __load(ApplicationContext $context) {
        $controllers_path = $context->getConfig()->getRootDir() . "/src/Controller";

        $controller_classnames = array_reduce(array_slice(scandir($controllers_path), 2), function($a, $v) {

            if(endsWith($v, "Controller.php"))
                $a[] = str_replace("Core", "Controller", __NAMESPACE__) . "\\" . explode('.', $v)[0];
            return $a;
        }, []);

        array_map(function($v) {
            $controller_name = str_replace("Controller", "", array_slice(explode('\\', $v), -1, 1)[0]);
            $lower_controller_name = strtolower($controller_name);
            $rc = new \ReflectionClass($v);

            foreach ($rc->getMethods() as $method) {
                if($method->isPublic()) {
                    $snake_method_name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $method->getName())), '_');
                    $route_name = $lower_controller_name . ":" . $method->getName();

                    $o = new stdClass();
                    $o->name = $route_name;
                    $o->url = "/$lower_controller_name/{$method->getName()}";
                    $o->page = "$lower_controller_name/$snake_method_name";

                    $this->_route_map[$route_name] = $o;
                }
            }
        }, $controller_classnames);

        $o = new stdClass();
        $o->name = "root";
        $o->url = "/";
        $o->page = "";
        $this->_route_map["root"] = $o;
    }

    /**
     * @param array $params
     * @param bool $with_start_char
     * @return string
     */
    private function __urlParamsEncode(array $params, bool $with_start_char = true) {
        $url_args = array_map(function($k) use ($params) {
            return "$k=" . $params[$k];
        }, array_keys($params));

        if(count($url_args))
            return (($with_start_char) ? "?" : "") . implode("&", $url_args);

        return "";
    }
}
