<?php

namespace App\Core\Request\Bag;

use App\Core\Request\RequestBag;

class Server extends RequestBag
{
    /**
     * @return string
     */
    public function uri(): string
    {
        return $this->get("REQUEST_URI");
    }
}
