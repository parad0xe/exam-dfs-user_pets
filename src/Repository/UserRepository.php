<?php

namespace App\Repository;

use App\Core\AbstractRepository;
use App\Entity\User;
use PDO;

class UserRepository extends AbstractRepository
{
    /**
     * @param int $id
     * @return User|false
     */
    public function find($id)
    {
        $query = $this->context->bdd()->prepare("SELECT * FROM users WHERE `id` = :id");
        $query->bindParam("id", $id);
        $query->setFetchMode(PDO::FETCH_CLASS, User::class);
        $query->execute();
        return $query->fetch();
    }

    /**
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function getUser(string $email, string $password) {
        $query = $this->context->bdd()->prepare("SELECT * FROM users WHERE `email` = :email AND `password` = :password");
        $query->bindParam("email", $email);
        $query->bindParam("password", $password);
        $query->execute();
        return $query->fetch();
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function exists(string $email)
    {
        $query = $this->context->bdd()->prepare("SELECT * FROM users WHERE `email` = :email");
        $query->bindParam("email", $email);
        $query->execute();
        return $query->fetch() !== false;
    }

    /**
     * Return ID of new value
     * @param User $new_user
     * @return int|null
     */
    public function persist(User $new_user)
    {
        $query = $this->context->bdd()->prepare("INSERT INTO users (`name`, `email`, `password`) VALUES (:name, :email, :password)");
        $query->bindValue("name", $new_user->getName());
        $query->bindValue("email", $new_user->getEmail());
        $query->bindValue("password", $new_user->getPassword());
        $res = $query->execute();

        if($res) {
            return $this->getUser($new_user->getEmail(), $new_user->getPassword())->id;
        }

        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        $query = $this->context->bdd()->prepare("UPDATE users SET `name` = :name, `email` = :email, `password` = :password WHERE `id` = :id");
        $query->bindValue("id", $user->getId());
        $query->bindValue("name", $user->getName());
        $query->bindValue("email", $user->getEmail());
        $query->bindValue("password", $user->getPassword());
        return $query->execute();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        if(!(new PetRepository($this->context))->deleteAllFor($id))
            return false;

        $query = $this->context->bdd()->prepare("DELETE FROM users WHERE `id` = :id");
        $query->bindValue("id", $id);
        return $query->execute();
    }
}
