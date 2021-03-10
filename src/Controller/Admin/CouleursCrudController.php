<?php

namespace App\Controller\Admin;

use App\Entity\Couleurs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouleursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Couleurs::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
