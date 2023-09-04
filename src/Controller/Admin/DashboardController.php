<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Video;
use App\Entity\Review;
use App\Entity\Episode;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('bundles\EasyAdminBundle\dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Netflux');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Video', 'fas fa-video', Video::class);
        yield MenuItem::linkToCrud('Genre', 'fas fa-masks-theater', Genre::class);
        yield MenuItem::linkToCrud('Episode', 'fas fa-list', Episode::class);
        yield MenuItem::linkToCrud('Review', 'fas fa-star', Review::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        yield MenuItem::linkToUrl('Retour à la place d\'accueil', 'fa fa-home', '/');
    }
}
