<?php

namespace App\Controller\Admin;

use App\Entity\Styles;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StylesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Styles::class;
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
