<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Core\ApplicationContext;
use App\Core\Response\ResponseInterface;
use App\Entity\User;
use App\Repository\UserRepository;

class AuthController extends AbstractController
{
    public $routes_request_auth = [
        "login" => false,
        "logout" => true
    ];

    public function login(): ResponseInterface
    {
        $request = $this->_context->request();

        if($request->post()->has("email") && $request->post()->has("password")) {
            $email = $request->post()->get("email");
            $password = $request->post()->get("password");

            $user_repository = new UserRepository($this->_context);

            $retrieve_user = $user_repository->getUser($email, $this->_context->auth()->hashPassword($password));

            if($retrieve_user) {
                $this->_context->auth()->login((new User())
                    ->setId($retrieve_user->id)
                    ->setName($retrieve_user->name)
                    ->setEmail($retrieve_user->email));

                $this->_context->request()->flash()->push("success", "Login successfully.");
                return $this->redirectTo("dashboard:index");
            } else {
                $this->_context->request()->flash()->push("errors", "Invalid credentials.");
            }
        }

        return $this->render("auth:login");
    }

    public function logout(): ResponseInterface
    {
        if($this->_context->auth()->isAuth()) {
            $this->_context->auth()->logout();
            $this->_context->request()->flash()->push("success", "Logout successfully.");
        }

        return $this->redirectTo("auth:login");
    }
}
