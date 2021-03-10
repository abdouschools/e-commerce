<?php

namespace App\Controller\Admin;


use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Form\CustomPropertyType;
use Doctrine\ORM\Cache\CollectionCacheKey;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Session\Session;

class CommandesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commandes::class;
    }
    public function configureFields(string $pageName): iterable
    {
        $livraison =  CollectionField::new('commande[livraison]')
            ->setTemplatePath('/articles/produit.html.twig')
            ->setLabel('livraison');
        $facturation =  CollectionField::new('commande[facturation]')
            ->setTemplatePath('/articles/produit.html.twig')
            ->setLabel('facturation');
        $produit =  CollectionField::new('commande[produit]')
            ->setTemplatePath('/articles/pr.html.twig')
            ->setLabel('produit');
        $totalttc =  Field::new('commande[prixTTC]', 'PrixTTC');
        $totalHT =  Field::new('commande[prixHt]', 'PrixHT');
        $numProduit =   CollectionField::new('commande[produit]');


        $fields =  [
            DateField::new('date'),
            IntegerField::new('reference'),
            AssociationField::new('utilisateur'),
            BooleanField::new('valider'),
            ArrayField::new('commande[tva]', 'TVA'),
            ArrayField::new('commande[prixTTC]'),
            ArrayField::new('commande[prixHt]'),


        ];
        if ($pageName == Crud::PAGE_INDEX) {
            $fields[] = $numProduit;
        } elseif ($pageName == Crud::PAGE_DETAIL) {
            $fields[] = $livraison;
            $fields[] = $facturation;
            $fields[] = $produit;
            $fields[] =  $totalttc;
            $fields[] =  $totalHT;
        } else {
            $fields[] = $livraison;
            $fields[] = $facturation;
            $fields[] = $produit;
        }
        return $fields;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail')
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }
}
