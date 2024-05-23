<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagController extends AbstractController
{
    #[Route('/tags/{id}')]
    public function showTagItems(EntityManagerInterface $entityManager, int $id): Response
    {

        $tag = $entityManager->getRepository(Tag::class)->find($id);
        $items = $tag->getItems();

        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
            'items' => $items,
        ]);

    }

    #[Route('/tags', name: 'app_tags', methods: [Request::METHOD_GET])]
    public function index(EntityManagerInterface $entityManager,
                          ItemService            $tagService,
                          Request                $request): JsonResponse
    {
        $query = $request->query->get('query');

        $tags = $tagService->getTagsByQuery($query);

        $tagsCollection = array();

        foreach ($tags as $tag){
            $tagsCollection[] = array(
                'id' => $tag->getId(),
                'name' => $tag->getName()
            );
        }

        return new JsonResponse($tagsCollection);
    }
}
