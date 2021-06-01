<?php


namespace App\Repository;


use App\Core\AbstractRepository;
use App\Entity\PetImage;

class PetImageRepository extends AbstractRepository
{
    /**
     * @param int $pet_image_id
     * @return mixed|false
     */
    public function find($pet_image_id)
    {
        $query = $this->context->bdd()->prepare("SELECT pi.* FROM pet_images pi 
            INNER JOIN pets p ON pi.pet_id = p.id 
            WHERE pi.id = :id AND p.user_id = :user_id
        ");
        $query->bindValue("id", $pet_image_id);
        $query->bindValue("user_id", $this->context->auth()->user()->getId());
        $query->setFetchMode(\PDO::FETCH_CLASS, PetImage::class);
        $query->execute();
        return $query->fetch();
    }

    /**
     * @param int $pet_id
     * @return array
     */
    public function findAllFor($pet_id)
    {
        $query = $this->context->bdd()->prepare("SELECT pi.* FROM pet_images pi 
            INNER JOIN pets p ON pi.pet_id = p.id 
            WHERE pi.pet_id = :pet_id AND p.user_id = :user_id
        ");
        $query->bindValue("pet_id", $pet_id);
        $query->bindValue("user_id", $this->context->auth()->user()->getId());
        $query->setFetchMode(\PDO::FETCH_CLASS, PetImage::class);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * @param int $pet_id
     * @return int
     */
    public function countFor($pet_id)
    {
        $query = $this->context->bdd()->prepare("SELECT COUNT(*) as _count FROM pet_images pi 
            INNER JOIN pets p ON pi.pet_id = p.id 
            WHERE pi.pet_id = :pet_id AND p.user_id = :user_id
        ");
        $query->bindValue("pet_id", $pet_id);
        $query->bindValue("user_id", $this->context->auth()->user()->getId());
        $query->setFetchMode(\PDO::FETCH_CLASS, PetImage::class);
        $query->execute();

        if(($c = $query->fetch()) != false)
            return $c->_count;
        return 0;
    }

    /**
     * @param PetImage $pet_image
     * @return bool
     */
    public function persist(PetImage $pet_image)
    {
        $query = $this->context->bdd()->prepare("INSERT INTO pet_images (`pet_id`, `name`, `web_uri`) VALUES (:pet_id, :name, :web_uri)");
        $query->bindValue("pet_id", $pet_image->getPetId());
        $query->bindValue("name", $pet_image->getName());
        $query->bindValue("web_uri", $pet_image->getWebUri());
        return $query->execute();
    }

    /**
     * @param int $pet_image_id
     * @param int $user_id
     * @return bool
     */
    public function delete($pet_image_id, $user_id)
    {
        $pet_image = $this->find($pet_image_id);

        if(!$pet_image)
            return false;

        unlink($this->context->getConfig()->getPublicDir() . $pet_image->getWebUri());

        $query = $this->context->bdd()->prepare("DELETE pi.* FROM pet_images pi
            INNER JOIN pets p ON p.id = pi.pet_id 
            WHERE pi.id = :id AND p.user_id = :user_id
        ");
        $query->bindValue("id", $pet_image_id);
        $query->bindValue("user_id", $user_id);
        return $query->execute();
    }

    /**
     * @param int $pet_id
     * @param int $user_id
     * @return bool
     */
    public function deleteFor($pet_id, $user_id)
    {
        $pet_images = $this->findAllFor($pet_id);

        foreach ($pet_images as $pet_image)
            unlink($this->context->getConfig()->getPublicDir() . $pet_image->getWebUri());

        $query = $this->context->bdd()->prepare("DELETE pi.* FROM pet_images pi
            INNER JOIN pets p ON pi.pet_id = p.id 
            WHERE pi.pet_id = :pet_id AND p.user_id = :user_id
        ");
        $query->bindValue("pet_id", $pet_id);
        $query->bindValue("user_id", $user_id);
        return $query->execute();
    }
}
