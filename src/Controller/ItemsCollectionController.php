<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemsCollection;
use App\Form\ItemsCollectionType;
use App\Form\ItemType;
use App\Service\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ItemsCollectionController extends AbstractController
{
    #[Route('/collections/create', name: 'app_collection_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemsCollectionType::class, new ItemsCollection());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $collection = $form->getData();
            $collection->setUser($this->getUser());

            $entityManager->persist($collection);
            $entityManager->flush();

            $this->addFlash('success', 'Collection successfully created.');

            return $this->redirectToRoute('app_main');
        }

        return $this->render('items_collection/form.html.twig', [
            'action' => 'Create',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/collections/{id}', name: 'app_collection', methods: [Request::METHOD_GET])]
    public function show(EntityManagerInterface $entityManager, ItemsCollection $collection, int $id): Response
    {
        $items = $collection->getItems();
        return $this->render('items_collection/show.html.twig', [
            'collection' => $collection,
            'items' => $items,
        ]);

    }

    #[Route('/collections/{id}/addItem', name: 'app_item_create', methods: ['GET', 'POST'])]
    public function addItem(ItemsCollection $collection,
                            Request $request,
                            EntityManagerInterface $entityManager,
                            ItemService $itemService): Response
    {


        $form = $this->createForm(ItemType::class, new Item($collection), [
            'custom_attributes' => $collection->getCustomItemAttributes()->toArray(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $collection->setUser($this->getUser());
            $item->setCreatedAt(new \DateTime());
            $tags = $form->get('tags')->getData();

            $itemService->assignTagsToItem($tags, $item);

            $itemService->persistCustomAttributes($form, $entityManager, $item, $collection);

            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item successfully added.');
            return $this->redirectToRoute('app_main');
        }

        return $this->render('item/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
