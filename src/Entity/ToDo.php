<?php

namespace App\Entity;

use App\Repository\ToDoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToDoRepository::class)
 */
class ToDo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $discription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $todo_name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="todo_list")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(string $discription): self
    {
        $this->discription = $discription;

        return $this;
    }

    public function getTodoName(): ?string
    {
        return $this->todo_name;
    }

    public function setTodoName(string $todo_name): self
    {
        $this->todo_name = $todo_name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
