<?php

namespace App\Core\Request;

abstract class RequestBag implements RequestBagInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * RequestBag constructor.
     * @param array $data
     */
    public function __construct(array &$data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return ($this->has($key)) ? $this->data[$key] : $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
