<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue
     * @Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"user"})
     */
    protected $name;

    /**
     * Stub method to represent the group defintion
     * @Groups({"user"})
     */
    public function getExtraField()
    {
        return "Example of extra serialization";
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}
