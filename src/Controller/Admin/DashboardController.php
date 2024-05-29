<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\ItemsCollection;
use App\Entity\ItemsCollectionCategory;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Final App Deploy');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);

        yield MenuItem::section('Collections');
        yield MenuItem::linkToCrud('Collections', 'fa fa-list', ItemsCollection::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-medium', ItemsCollectionCategory::class);
        yield MenuItem::linkToCrud('Item', 'fa fa-item', Item::class);
    }
}
