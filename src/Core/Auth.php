<?php


namespace App\Core;


use App\Core\Request\Request;
use App\Entity\User;

class Auth
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->request->session()->has("user");
    }

    /**
     * @return User|null
     */
    public function user() {
        return ($this->isAuth()) ? $this->request->session()->get("user") : null;
    }

    /**
     * @param User $user
     */
    public function login(User $user) {
        $this->request->session()->set("user", $user);
    }

    public function logout()
    {
        $this->request->session()->unset("user");
    }

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public function passwordCheck($password, $hash): bool {
        return hash("sha256", $password) === $hash;
    }

    /**
     * @param $password
     * @return string
     */
    public function hashPassword($password): string {
        return hash("sha256", $password);
    }
}
