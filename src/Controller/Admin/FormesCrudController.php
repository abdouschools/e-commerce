<?php

namespace App\Controller\Admin;

use App\Entity\Formes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FormesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formes::class;
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
