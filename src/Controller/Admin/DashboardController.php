<?php

namespace App\Controller\Admin;

use App\Entity\Actualite;
use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Commandes;
use App\Entity\Comments;
use App\Entity\Couleurs;
use App\Entity\Formes;
use App\Entity\Marques;
use App\Entity\Styles;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{




    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end user
            ->setTitle('Griffa')
            // you can include HTML contents too (e.g. to link to an image)
            ->setTitle('<img src="image/logo-final.png" style="width : 100px ; border-radius : 5px" > <span class="text-small" style="color : rgb(70, 201, 66)">Griffa </span>  <span class="text-small" style="color : rgb(248, 130, 20)"> lUNETTES</span>')

            // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('image/logo-final.png')

            // the domain used by default is 'messages'
            ->setTranslationDomain('my-custom-domain')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')


            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            ->renderSidebarMinimized()

            // by default, all backend URLs include a signature hash. If a user changes any
            // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
            // triggers an error. If this causes any issue in your backend, call this method
            // to disable this feature and remove all URL signature checks
            ->disableUrlSignatures();
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(CategoriesCrudController::class)->generateUrl());
    }
    public function configureMenuItems(): iterable

    {


        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
        yield MenuItem::section('Menu');

        yield MenuItem::linkToCrud('Articles', 'fas fa-archive', Articles::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-tags', Categories::class);
        yield MenuItem::linkToCrud('Couleurs', 'fas fa-tint', Couleurs::class);
        yield MenuItem::linkToCrud('Formes', 'fas fa-shapes', Formes::class);
        yield MenuItem::linkToCrud('Marques', 'fas fa-award', Marques::class);
        yield MenuItem::linkToCrud('Styles', 'fas fa-glasses', Styles::class);
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('User', 'fa fa-user', User::class)
                ->setPermission('ROLE_ADMIN');
        }
        yield MenuItem::linkToCrud('Comments', 'fa fa-comment', Comments::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-dolly', Commandes::class);
        yield MenuItem::linkToCrud('Actualite', 'far fa-newspaper', Actualite::class);
    }
}
