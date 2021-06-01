<?php


namespace App\Controller;


use App\Core\AbstractController;
use App\Core\Response\ResponseInterface;
use App\Entity\Pet;
use App\Repository\PetImageRepository;
use App\Repository\PetRepository;

class DashboardController extends AbstractController
{
    public $routes_request_auth = [
        "index" => true
    ];

    public function index(): ResponseInterface
    {
        $user = $this->_context->request()->session()->get("user");
        $pets = (new PetRepository($this->_context))->findAllFor($user->getId());

        $pet_image_count_array = array_reduce($pets, function($a, $pet) {
            /**
             * @var Pet $pet
             */
            $a[$pet->getId()] = (new PetImageRepository($this->_context))->countFor($pet->getId());
            return $a;
        }, []);

        return $this->render("dashboard:index", [
            "user" => $user,
            "pets" => $pets,
            "pet_image_count_array" => $pet_image_count_array
        ]);
    }
}
