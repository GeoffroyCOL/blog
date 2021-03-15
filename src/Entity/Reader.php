<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReaderRepository;
use ApiPlatform\Core\Annotation\ApiResource;


#[ApiResource(
    denormalizationContext: [
        "groups" => ["reader.write"]
    ],

    collectionOperations: [
        "GET" => [
            "security_post_denormalize" => "is_granted('ROLE_ADMIN')"
        ],
        "POST"
    ],
    itemOperations: [
        "GET" => [
            "security" => "is_granted('ACCESS_USER', object)"
        ], 
    ],
)]

/**
 * @ORM\Entity(repositoryClass=ReaderRepository::class)
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
