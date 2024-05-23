<?php

namespace App\Entity;

use App\Repository\ItemAttributeValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemAttributeValueRepository::class)]
#[ORM\Table(name: '`item_attribute_values`')]
class ItemAttributeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CustomItemAttribute::class)]
    private ?CustomItemAttribute $attribute = null;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'attributes')]
    private ?Item $item = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttribute(): ?CustomItemAttribute
    {
        return $this->attribute;
    }

    public function setAttribute(?CustomItemAttribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): void
    {
        $this->item = $item;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}
