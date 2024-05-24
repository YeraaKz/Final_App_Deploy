<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\ItemAttributeValue;
use App\Entity\ItemsCollection;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class ItemService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTagsByQuery(?string $query = null): array
    {
        if ($query) {
            return $this->entityManager->getRepository(Tag::class)->findByQuery($query);
        }

        return $this->entityManager->getRepository(Tag::class)->findAll();

    }
    public function assignTagsToItem(string $tags, Item $item)
    {
        if($tags){
            $tagNames = explode(' ', $tags);
            foreach ($tagNames as $tagName) {

                $tag = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => $tagName]);

                if (!$tag) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $this->entityManager->persist($tag);
                }

                $item->addTag($tag);
            }
        }
    }
    public function persistCustomAttributes(FormInterface $form,
                                            EntityManagerInterface $entityManager,
                                            Item $item,
                                            ItemsCollection $collection): void
    {
        foreach ($collection->getCustomItemAttributes() as $attribute) {
            $attributeFormField = $form->get(str_replace(' ', '_', $attribute->getName()));
            $attributeValue = new ItemAttributeValue();
            $attributeValue->setAttribute($attribute);
            $attributeValue->setItem($item);
            $attributeValue->setValue($attributeFormField->getData());
            $entityManager->persist($attributeValue);
        }
    }
}