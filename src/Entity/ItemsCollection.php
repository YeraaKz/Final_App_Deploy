<?php

namespace App\Entity;

use App\Repository\ItemsCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemsCollectionRepository::class)]
#[ORM\Table(name: '`items_collections`')]
class ItemsCollection
{
    public function __construct()
    {
        $this->customItemAttributes = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(groups: ['update'])]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[Assert\NotNull()]
    private ?ItemsCollectionCategory $category = null;

    #[ORM\OneToMany(targetEntity: CustomItemAttribute::class, mappedBy: 'itemCollection', cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid()]
    private Collection $customItemAttributes;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'collection', cascade: ['persist', 'remove'])]
    private Collection $items;

    #[ORM\ManyToOne(inversedBy: 'collections')]
    #[ORM\JoinColumn('user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): ?ItemsCollectionCategory
    {
        return $this->category;
    }

    public function setCategory(?ItemsCollectionCategory $category): void
    {
        $this->category = $category;
    }

    public function getCustomItemAttributes(): Collection
    {
        return $this->customItemAttributes;
    }

    public function setCustomItemAttributes(Collection $customItemAttributes): void
    {
        $this->customItemAttributes = $customItemAttributes;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(Collection $items): void
    {
        $this->items = $items;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
