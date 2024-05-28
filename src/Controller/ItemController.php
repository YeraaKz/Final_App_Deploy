<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Item;
use App\Entity\Like;
use App\Entity\Tag;
use App\Form\CommentType;
use App\Util\MarkdownParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ItemController extends AbstractController
{
    #[Route('/{_locale<%app.supported_locales%>}/items', name: 'app_items', methods: [Request::METHOD_GET])]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

        $items = $entityManager->getRepository(Item::class)->findAll();

        if($request->query->has('tag_id')) {
            $tag = $entityManager->getRepository(Tag::class)->find($request->query->get('tag_id'));

            $items = $tag->getItems();
        }

        return $this->render('item/index.html.twig', [
            'tag' => $tag,
            'items' => $items
        ]);

    }

    #[Route('/{_locale<%app.supported_locales%>}/items/{id}', name: 'app_item')]
    public function show(Request $request,
                         EntityManagerInterface $entityManager,
                         MarkdownParser $markdownParser,
                         int $id): Response
    {
        $item = $entityManager->getRepository(Item::class)->find($id);

        $description = $markdownParser->parse($item->getDescription());

        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setUser($this->getUser());
            $comment->setItem($item);
            $comment->setCreatedAt(new \DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

        }

        return $this->render('item/show.html.twig', [
            'item' => $item,
            'description' => $description,
            'comments' => $item->getComments(),
            'form' => $form->createView(),

        ]);
    }

    #[Route('/api/items/{id}/like', name: 'app_item_like', methods: [Request::METHOD_POST])]
    public function likeItem(Item $item, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $existingLike = $entityManager->getRepository(Like::class)->findByUserAndItem($user, $item);

        if ($existingLike) {
            $entityManager->remove($existingLike);
            $entityManager->flush();

            return new JsonResponse(['liked' => false]);
        }

        $like = new Like();
        $like->setUser($user);
        $like->setItem($item);

        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json([
            'liked' => true,
        ]);
    }

    #[Route('/api/items/{id}/comments')]
    public function getItemComment(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $comments = $entityManager->getRepository(Comment::class)->findByCreatedDateAsc($id);
        $responseData = array_map(function ($comment) {
            return [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'author' => $comment['author'],
                'createdAt' => $comment['createdAt']->format('Y-m-d H:i:s')
            ];
        }, $comments);

        return $this->json($responseData);
    }
}
