<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemsCollection;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $tags = $entityManager->getRepository(Tag::class)->findAll();

        $collections = $entityManager->getRepository(ItemsCollection::class)->findBiggestCollections(5);

        $items = $entityManager->getRepository(Item::class)->findLatestItems(10);

        $tagData = [];
        foreach ($tags as $tag) {
            $tagData[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }

        return $this->render('main/index.html.twig', [
            'tags' => $tagData,
            'collections' => $collections,
            'items' => $items,
        ]);
    }
}
