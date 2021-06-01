<?php


namespace App\Controller;


use App\Core\AbstractController;
use App\Core\Response\ResponseInterface;
use App\Entity\PetImage;
use App\Repository\PetImageRepository;
use App\Repository\PetRepository;

class PetController extends AbstractController
{
    public $routes_request_auth = [
        "delete" => true,
        "imageView" => true,
        "imageDelete" => true,
        "uploadImage" => true,
    ];

    public function delete($id): ResponseInterface
    {
        if($id) {
            if((new PetRepository($this->_context))->delete($id, $this->_context->auth()->user()->getId())) {
                $this->_context->request()->flash()->push("success", "Pet removed successfully.");
                return $this->redirectTo("root");
            } else {
                $this->_context->request()->flash()->push("errors", "Missing information.");
                return $this->redirectTo("root");
            }
        } else {
            $this->_context->request()->flash()->push("errors", "Missing information.");
            return $this->redirectTo("root");
        }
    }

    public function imageView($id): ResponseInterface
    {
        if(!$id) {
            $this->_context->request()->flash()->push("errors", "Missing information.");
            return $this->redirectTo("root");
        }

        $pet = (new PetRepository($this->_context))->find($id, $this->_context->auth()->user()->getId());

        if(!$pet) {
            $this->_context->request()->flash()->push("errors", "An error occurred.");
            return $this->redirectTo("root");
        }

        $images = (new PetImageRepository($this->_context))->findAllFor($id);

        return $this->render("pet:imageView", [
            "images" => $images,
            "pet" => $pet
        ]);
    }

    public function imageDelete($id, $image_id): ResponseInterface {
        if($id && $image_id) {
            if((new PetImageRepository($this->_context))->delete($image_id, $this->_context->auth()->user()->getId())) {
                $this->_context->request()->flash()->push("success", "Image deleted successfully.");
                return $this->redirectTo("pet:imageView", ["id" => $id]);
            } else {
                $this->_context->request()->flash()->push("errors", "An error is occurred.");
                return $this->redirectTo("pet:imageView", ["id" => $id]);
            }
        } else {
            $this->_context->request()->flash()->push("errors", "Missing information.");
            return $this->redirectTo("pet:imageView", ["id" => $id]);
        }
    }

    public function uploadImage(): ResponseInterface
    {
        $id = $this->_context->request()->post()->get("id");
        $files = $this->_context->request()->files();

        if($files->has('file') && $files->get('file')["size"] > 0) {
            if($id) {
                $file = $files->get('file');

                $hashname = sha1($file["name"] . uniqid());
                $ext = array_slice(explode('.', $file['name']), -1, 1);

                if(count($ext) == 0) {
                    $this->_context->request()->flash()->push("errors", "Undefined extension.");
                    return $this->redirectTo("root");
                }

                $ext = $ext[0];

                $pet_image = (new PetImage())
                    ->setPetId($id)
                    ->setName($file['name'])
                    ->setWebUri("/images/pets/$hashname.$ext");

                if(!file_exists($this->_context->getConfig()->getPublicDir() . "/images/pets/")) {
                    mkdir($this->_context->getConfig()->getPublicDir() . "/images/pets/", 0777, true);
                }

                if (move_uploaded_file($file['tmp_name'], $this->_context->getConfig()->getPublicDir() . "/images/pets/$hashname.$ext")) {
                    if((new PetImageRepository($this->_context))->persist($pet_image)) {
                        $this->_context->request()->flash()->push("success", "Image uploaded successfully.");
                        return $this->redirectTo("root");
                    } else {
                        $this->_context->request()->flash()->push("errors", "An error is occurred.");
                        return $this->redirectTo("root");
                    }
                } else {
                    $this->_context->request()->flash()->push("errors", "Upload failed.");
                    return $this->redirectTo("root");
                }
            } else {
                $this->_context->request()->flash()->push("errors", "Missing information.");
                return $this->redirectTo("root");
            }
        } else {
            $this->_context->request()->flash()->push("errors", "No file to upload.");
            return $this->redirectTo("root");
        }
    }
}
