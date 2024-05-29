<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\ItemsCollection;
use App\Form\ItemsCollectionType;
use App\Form\ItemType;
use App\Service\ItemService;
use App\Service\S3Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale<%app.supported_locales%>}')]
class ItemsCollectionController extends AbstractController
{
    private $s3Service;

    public function __construct(S3Service $s3Service)
    {
        $this->s3Service = $s3Service;
    }

    #[Route('/collections/create', name: 'app_collection_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request,
                           EntityManagerInterface $entityManager,
                           TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ItemsCollectionType::class, new ItemsCollection());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $collection = $form->getData();
            $collection->setUser($this->getUser());

            $entityManager->persist($collection);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('flash.collection_created'));

            return $this->redirectToRoute('app_main');
        }

        return $this->render('items_collection/form.html.twig', [
            'action' => 'Create',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/collections/{id}/edit', name: 'app_collection_edit', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function edit(Request $request, EntityManagerInterface $entityManager, ItemsCollection $collection): Response
    {
        $form = $this->createForm(ItemsCollectionType::class, $collection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Collection successfully updated.');
            return $this->redirectToRoute('app_user_collections', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('items_collection/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Edit',
        ]);
    }

    #[Route('/collections/{id}/delete', name: 'app_collection_delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, EntityManagerInterface $entityManager, ItemsCollection $collection): Response
    {
        if ($this->isCsrfTokenValid('delete' . $collection->getId(), $request->request->get('_token'))) {
            $entityManager->remove($collection);
            $entityManager->flush();

            $this->addFlash('success', 'Collection successfully deleted.');
        }

        return $this->redirectToRoute('app_user_collections', ['id' => $this->getUser()->getId()]);
    }

    #[Route('/collections/{id}', name: 'app_collection', methods: [Request::METHOD_GET])]
    public function show(Request $request, EntityManagerInterface $entityManager, ItemsCollection $collection, int $id): Response
    {
//        $items = $collection->getItems();

        $sortField = $request->query->get('sort', 'name');
        $sortOrder = $request->query->get('order', 'asc');

        $items = $entityManager->getRepository(Item::class)->findByCollection($collection, $sortField, $sortOrder);

        return $this->render('items_collection/show.html.twig', [
            'collection' => $collection,
            'items' => $items,
        ]);

    }

    #[Route('/collections/{id}/addItem', name: 'app_item_create', methods: ['GET', 'POST'])]
    #[IsGranted('edit', subject: 'collection')]
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
            $tags = $form->get('tags')->getData();

            $image = $form->get('imageFile')->getData();

            if ($image) {
                $itemKey = $this->s3Service->uploadFile($image);
                $item->setItemKey($itemKey);
            }

            $itemService->assignTagsToItem($tags, $item);

            $itemService->persistCustomAttributes($form, $entityManager, $item, $collection);

            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item successfully added.');
            return $this->redirectToRoute('app_main');
        }

        return $this->render('item/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Add'
        ]);
    }

    #[Route('/collections/{id}/items/{itemId?}/editItem', name: 'app_item_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', subject: 'collection')]
    public function editItem(ItemsCollection $collection, ?int $itemId, Request $request, EntityManagerInterface $entityManager, ItemService $itemService): Response
    {
        $item = $itemId ? $entityManager->getRepository(Item::class)->find($itemId) : new Item($collection);

        $form = $this->createForm(ItemType::class, $item, [
            'custom_attributes' => $collection->getCustomItemAttributes()->toArray(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            if (!$itemId) {
                $item->setCreatedAt(new \DateTime());
            }
            $tags = $form->get('tags')->getData();
            $itemService->assignTagsToItem($tags, $item);
            $itemService->persistCustomAttributes($form, $entityManager, $item, $collection);

            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item ' . ($itemId ? 'updated' : 'added') . ' successfully.');
            return $this->redirectToRoute('app_main');
        }

        return $this->render('item/form.html.twig', [
            'form' => $form->createView(),
            'action' => 'Edit',
            'item' => $item,
        ]);
    }


}
