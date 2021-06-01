<?php


namespace App\Repository;


use App\Core\AbstractRepository;
use App\Entity\Pet;
use App\Entity\User;

class PetRepository extends AbstractRepository
{

    /**
     * @param int $pet_id
     * @param int $user_id
     * @return mixed
     */
    public function find($pet_id, $user_id)
    {
        $query = $this->context->bdd()->prepare("SELECT * FROM pets WHERE `id` = :id AND `user_id` = :user_id");
        $query->bindValue("id", $pet_id);
        $query->bindValue("user_id", $user_id);
        $query->setFetchMode(\PDO::FETCH_CLASS, Pet::class);
        $query->execute();
        return $query->fetch();
    }

    /**
     * @param int $user_id
     * @return Pet[]
     */
    public function findAllFor(int $user_id)
    {
        $query = $this->context->bdd()->prepare("SELECT * FROM pets WHERE `user_id` = :user_id");
        $query->bindValue("user_id", $user_id);
        $query->setFetchMode(\PDO::FETCH_CLASS, Pet::class);
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * @param Pet $new_pet
     * @return bool
     */
    public function persist(Pet $new_pet)
    {
        $query = $this->context->bdd()->prepare("INSERT INTO pets (`user_id`, `name`, `type`, `race`) VALUES (:user_id, :name, :type, :race)");
        $query->bindValue("user_id", $new_pet->getUserId());
        $query->bindValue("name", $new_pet->getName());
        $query->bindValue("type", $new_pet->getType());
        $query->bindValue("race", $new_pet->getRace());
        return $query->execute();
    }

    /**
     * @param int $pet_id
     * @param int $user_id
     * @return bool
     */
    public function delete($pet_id, $user_id): bool
    {
        if(!(new PetImageRepository($this->context))->deleteFor($pet_id, $user_id))
            return false;

        $query = $this->context->bdd()->prepare("DELETE FROM pets WHERE `id` = :id AND `user_id` = :user_id");
        $query->bindValue("id", $pet_id);
        $query->bindValue("user_id", $user_id);
        return $query->execute();
    }

    /**
     * @param int $user_id
     * @return bool
     */
    public function deleteAllFor(int $user_id)
    {
        $pets = $this->findAllFor($user_id);

        if($pets === false)
            return false;

        foreach ($pets as $pet)
            $this->delete($pet->getId(), $user_id);

        return true;
    }
}
