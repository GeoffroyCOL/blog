<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    attributes: [
        "pagination_items_per_page" => 10,
        "security"                  => "is_granted('ROLE_ADMIN')"
    ],
    denormalizationContext: [
        "groups" => ["category.write"]
    ],
    itemOperations: [
        "GET",
        "PUT",
        "DELETE"
    ]
)]

#[ApiFilter(SearchFilter::class, properties: ["name" => "partial"])]

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="{{ value }} est déjà utilisé."
 * )
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      minMessage = "Ce champ doit posséder {{ limit }} caractères.",
     * )
     *
     * @Groups({
     *      "category.write"
     * })
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * @param  string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    
    /**
     * @param  string $slug
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
