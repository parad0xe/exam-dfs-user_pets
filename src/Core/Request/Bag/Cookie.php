<?php


namespace App\Core\Request\Bag;


use App\Core\Request\RequestBag;

class Cookie extends RequestBag
{
    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value) {
        setcookie($key, $value, 0, "/");
    }

    /**
     * @param string $key
     */
    public function delete(string $key) {
        if($this->has($key)) {
            unset($_COOKIE[$key]);
        }
    }
}
