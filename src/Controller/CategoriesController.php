<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoriesController extends AbstractController
{

    /**
     * @Route("/categories", name="categories")
     */
    public function showAll(TranslatorInterface $translator)
    {
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $message = $translator->trans('no style with this name');
        if (!$categories) {
            throw $this->createNotFoundException($message);
        }
        return $this->render("categories/showall.html.twig", [
            'categories' => $categories,


        ]);
    }

    /**
     * @Route("/categorie-show/{id<\d+>}", name="categorie-show")
     */
    public function show($id, PaginatorInterface $paginator, Request $request, TranslatorInterface $translator)
    {

        $categories = $this->getDoctrine()->getRepository(Categories::class)->find($id);
        $message = $translator->trans("la categorie n'existe pas");
        if (!$categories) {
            throw $this->createNotFoundException($message);
        }
        $article =  $categories->getArticles();
        $articles = $paginator->paginate(
            $article, //hna hatina les articles li jabnahom
            $request->query->getInt('page', 1), //hna hatina numero ta la page w 1 aw darnah la malgahach yhat 1 par default
            6 // hna ma3naha hatana 4 article par page
        );
        return $this->render('categories/show.html.twig', [
            'categories' => $categories,
            'articles' => $articles

        ]);
    }
}
