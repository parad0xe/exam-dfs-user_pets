<?php


namespace App\Core\Response;


class RedirectResponse implements ResponseInterface
{
    public function __construct($uri)
    {
        header("Location: $uri");
        die;
    }
}
