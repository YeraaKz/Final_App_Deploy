<?php

namespace App\Controller;

use App\Entity\ItemsCollection;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users/{id}/collections', name: 'app_user_collections')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {

        $user = $entityManager->getRepository(User::class)->find($id);
        $collections = $entityManager->getRepository(ItemsCollection::class)->findByUserId($id);

        return $this->render('items_collection/index.html.twig', [
            'user' => $user,
            'collections' => $collections
        ]);
    }
}
