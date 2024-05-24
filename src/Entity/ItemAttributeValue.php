<?php

namespace App\Entity;

use App\Enum\CustomItemAttributeDatatype;
use App\Repository\ItemAttributeValueRepository;
use DateTime;
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

    #[ORM\Column(type: 'json')]
    private $value = null;

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

    public function getValue()
    {
        switch ($this->attribute->getType()) {
            case CustomItemAttributeDatatype::Date->value:
                return new \DateTime($this->value);
            case CustomItemAttributeDatatype::Integer->value:
                return (int) $this->value;
            case CustomItemAttributeDatatype::Boolean->value:
                return in_array(strtolower($this->value), ['1', 'true', 'on', 'yes'], true);
            default:
                return $this->value;
        }
    }

    public function setValue($value): void
    {
        switch ($this->attribute->getType()->value) {
            case CustomItemAttributeDatatype::Date->value:
                if ($value instanceof \DateTimeInterface) {
                    $this->value = $value->format('Y-m-d');
                } else {
                    $this->value = (new DateTime($value))->format('Y-m-d');
                }
                break;
            case CustomItemAttributeDatatype::Integer->value:
                $this->value = (int) $value;
                break;
            case CustomItemAttributeDatatype::Boolean->value:
                $this->value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            default:
                $this->value = (string) $value;
                break;
        }
    }



}
