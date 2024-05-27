<?php

namespace App\Entity;

use App\Enum\CustomItemAttributeDatatype;
use App\Repository\CustomItemAttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomItemAttributeRepository::class)]
#[ORM\Table(name: '`custom_item_attributes`')]
class CustomItemAttribute
{
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

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'customItemAttributes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ItemsCollection $itemCollection = null;

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
}
