<?php

namespace App\Entity;

use App\Enum\CustomItemAttributeDatatype;
use App\Repository\CustomItemAttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomItemAttributeRepository::class)]
#[ORM\Table(name: '`custom_item_attributes`')]
class CustomItemAttribute
{
    public function __construct()
    {
        $this->itemAttributeValue = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    #[Assert\Length(min: 3, max: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 10, enumType: CustomItemAttributeDatatype::class)]
    #[Assert\NotNull()]
    #[Assert\Type(type: CustomItemAttributeDatatype::class)]
    private ?CustomItemAttributeDatatype $type = null;

    #[ORM\ManyToOne(inversedBy: 'customItemAttributes')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?ItemsCollection $itemCollection = null;

    #[ORM\OneToMany(
        targetEntity: ItemAttributeValue::class,
        mappedBy: 'attribute',
        cascade: ['remove'],
        orphanRemoval: true)]
    private Collection $itemAttributeValue;

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

    public function getType(): ?CustomItemAttributeDatatype
    {
        return $this->type;
    }

    public function setType(?CustomItemAttributeDatatype $type): void
    {
        $this->type = $type;
    }

    public function getItemCollection(): ?ItemsCollection
    {
        return $this->itemCollection;
    }

    public function setItemCollection(?ItemsCollection $itemCollection): void
    {
        $this->itemCollection = $itemCollection;
    }

    public function getItemAttributeValue(): Collection
    {
        return $this->itemAttributeValue;
    }

    public function setItemAttributeValue(Collection $itemAttributeValue): void
    {
        $this->itemAttributeValue = $itemAttributeValue;
    }


}
