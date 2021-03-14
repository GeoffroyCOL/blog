<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReaderRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ReaderRepository::class)
 * 
 * 
 * @ApiResource(
 * 
 *      denormalizationContext={"groups"={"reader.write"}},
 * 
 *      collectionOperations={
 *          "GET",
 *          "POST"
 *      },
 *      itemOperations={
 *          "GET"
 *      }
 * )
 */
class Reader extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

        $this->setRoles(['ROLE_READER']);
    }
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
