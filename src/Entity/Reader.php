<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReaderRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

#[ApiResource(

    attributes: [
        "pagination_items_per_page" => 10
    ],

    denormalizationContext: [
        "groups" => ["reader.write"]
    ],

    collectionOperations: [
        "GET" => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        "POST"
    ],
    itemOperations: [
        "GET" => [
            "security" => "is_granted('ACCESS_USER', object)"
        ], 
    ],
)]

#[ApiFilter(
    DateFilter::class, properties: [
        'createdAt'     => DateFilter::EXCLUDE_NULL,
        'connectedAt'   => DateFilter::EXCLUDE_NULL
    ]
)]

#[ApiFilter(
    SearchFilter::class, properties: [
        'username' => 'partial'
    ]
)]

#[ApiFilter(
    OrderFilter::class, properties: [
        'username'
    ], 
    arguments: [
        'orderParameterName' => 'order'
    ]
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
