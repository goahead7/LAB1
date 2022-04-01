<?php
/**
 * File
 * php version 8.0
 *
 * @category Entity
 * @package  App\Entity
 * @author   Kuznetsova Sophia <sophia.kuznetsova.7@gmail.com>
 * @license  https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt /
 * somename
 * BSD Licence
 * @link     https://github.com/goahead7/LAB1
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

class File
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }
}
