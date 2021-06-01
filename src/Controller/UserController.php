<?php


namespace App\Controller;


use App\Core\AbstractController;
use App\Core\ApplicationContext;
use App\Core\Response\ResponseInterface;
use App\Entity\Pet;
use App\Entity\User;
use App\Repository\PetRepository;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    public $routes_request_auth = [
        "create" => false,
        "update" => true,
        "delete" => true,
        "addPet" => true
    ];

    private $_user_repository;

    public function __construct(ApplicationContext $context)
    {
        parent::__construct($context);
        $this->_user_repository = new UserRepository($context);
    }

    public function create(): ResponseInterface {
        $name = $this->_context->request()->post()->get("name");
        $email = $this->_context->request()->post()->get("email");
        $password = $this->_context->request()->post()->get("password");
        $repeat_password = $this->_context->request()->post()->get("repeat_password");

        if($this->_context->request()->post()->has("submit")) {
            if($name && $email && $password && $repeat_password) {
                if(!$this->_user_repository->exists($email)) {
                    if($password == $repeat_password) {
                        $new_user = new User();
                        $new_user->setEmail($email)
                            ->setName($name)
                            ->setPassword($this->_context->auth()->hashPassword($password));

                        $id = $this->_user_repository->persist($new_user);

                        if($id) {
                            $new_user->setId($id);
                            $this->_context->auth()->login($new_user);
                            $this->_context->request()->flash()->push("success", "Account created successfully.");
                            return $this->redirectTo("dashboard:index");
                        } else {
                            $this->_context->request()->flash()->push("errors", "An error occurred.");
                        }
                    } else {
                        $this->_context->request()->flash()->push("errors", "Repeat password not match.");
                    }
                } else {
                    $this->_context->request()->flash()->push("errors", "Email already exist.");
                }
            } else {
                $this->_context->request()->flash()->push("errors", "Missing information.");
            }
        }

        return $this->render("user:create", [
            "name" => $name,
            "email" => $email
        ]);
    }

    public function update(): ResponseInterface {
        $user = $this->_user_repository->find($this->_context->auth()->user()->getId());

        $name = $this->_context->request()->post()->get("name", $user->getName());
        $email = $this->_context->request()->post()->get("email", $user->getEmail());
        $password = $this->_context->request()->post()->get("password");
        $repeat_password = $this->_context->request()->post()->get("repeat_password");
        $current_password = $this->_context->request()->post()->get("current_password");

        if($this->_context->request()->post()->has("submit")) {
            if($name && $email) {
                if(!$this->_user_repository->exists($email) || $email === $user->getEmail()) {
                    if($password == $repeat_password || $password === "") {
                        if($this->_context->auth()->passwordCheck($current_password, $user->getPassword())) {
                            $user->setEmail($email)
                                ->setName($name);

                            if($password !== "") {
                                $user->setPassword($this->_context->auth()->hashPassword($password));
                            }

                            if($this->_user_repository->update($user)) {
                                $user->setPassword("");
                                $this->_context->auth()->login($user);
                                $this->_context->request()->flash()->push("success", "Account updated successfully.");
                                return $this->redirectTo("user:update");
                            } else {
                                $this->_context->request()->flash()->push("errors", "An error occurred.");
                            }
                        } else {
                            $this->_context->request()->flash()->push("errors", "Invalid credentials.");
                        }
                    } else {
                        $this->_context->request()->flash()->push("errors", "Repeat password not match.");
                    }
                } else {
                    $this->_context->request()->flash()->push("errors", "Email already exist.");
                }
            } else {
                $this->_context->request()->flash()->push("errors", "Missing information.");
            }
        }

        return $this->render("user:update", [
            "id" => $user->getId(),
            "name" => $name,
            "email" => $email
        ]);
    }

    public function delete($id): ResponseInterface {
        if($id) {
            if($this->_user_repository->delete($id)) {
                $this->_context->auth()->logout();
                $this->_context->request()->flash()->push("success", "Account removed successfully.");
                return $this->redirectTo("auth:login");
            } else {
                $this->_context->request()->flash()->push("errors", "An error occurred.");
                return$this->redirectTo("dashboard:index");
            }
        } else {
            $this->_context->request()->flash()->push("errors", "Invalid request.");
            return$this->redirectTo("dashboard:index");
        }
    }

    public function addPet(): ResponseInterface
    {
        $name = $this->_context->request()->post()->get("name");
        $type = $this->_context->request()->post()->get("type");
        $race = $this->_context->request()->post()->get("race");

        if($this->_context->request()->post()->has("submit")) {
            if($name && $type && $race) {
                $new_pet = (new Pet())
                    ->setUserId($this->_context->auth()->user()->getId())
                    ->setName($name)
                    ->setRace($race)
                    ->setType($type);

                $pet_repository = new PetRepository($this->_context);
                if($pet_repository->persist($new_pet)) {
                    $this->_context->request()->flash()->push("success", "Pet added successfully.");
                    return $this->redirectTo("root");
                } else {
                    $this->_context->request()->flash()->push("errors", "An error occurred.");
                }
            } else {
                $this->_context->request()->flash()->push("errors", "Missing information.");
            }
        }

        return $this->redirectTo("dashboard:index");
    }
}
