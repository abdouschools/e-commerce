<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\AttachmentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ArticlesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Articles::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $imageFile =  CollectionField::new('attachments')
            ->setEntryType(AttachmentType::class)
            ->setFormTypeOption('by_reference', false)
            ->onlyOnForms()
            ->setLabel('image');
        $image =  CollectionField::new('attachments')
            ->setTemplatePath('/articles/images.html.twig')
            ->setLabel('image');



        $fields =  [

            TextField::new('nom'),
            TextField::new('meta'),
            TextEditorField::new('description'),
            NumberField::new('prix'),
            NumberField::new('tvaMultiplication'),
            TextField::new('tvaNom'),
            NumberField::new('valeur'),


            NumberField::new('poids'),
            AssociationField::new('categorie'),
            AssociationField::new('style'),
            AssociationField::new('marques'),
            AssociationField::new('formes'),
            AssociationField::new('couleurs'),
            AssociationField::new('comments'),
            TextField::new('imageAlt'),




        ];
        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $image;
        } else {
            $fields[] = $imageFile;
        }
        return $fields;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }
}
