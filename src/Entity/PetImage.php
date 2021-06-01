<?php


namespace App\Entity;


class PetImage
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int|null
     */
    private $pet_id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $web_uri;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return PetImage
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPetId()
    {
        return $this->pet_id;
    }

    /**
     * @param mixed $pet_id
     * @return PetImage
     */
    public function setPetId($pet_id)
    {
        $this->pet_id = $pet_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return PetImage
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebUri()
    {
        return $this->web_uri;
    }

    /**
     * @param mixed $web_uri
     * @return PetImage
     */
    public function setWebUri($web_uri)
    {
        $this->web_uri = $web_uri;
        return $this;
    }
}
