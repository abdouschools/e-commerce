<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Actualite;
use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Marques;
use App\Entity\Styles;
use App\Form\SearchType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function last(Request $request): Response

    {
        $marques = $this->getDoctrine()->getRepository(Marques::class)->findAll();

        $styles = $this->getDoctrine()->getRepository(Styles::class)->findAll();

        $articles = $this->getDoctrine()->getRepository(Articles::class)->recuperation();
        $homme = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(array(
            'nomCategorie' => 'homme'
        ), null, null);
        $femme = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(array(
            'nomCategorie' => 'femme'
        ), null, null);
        $enfant = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(array(
            'nomCategorie' => 'enfant'
        ), null, null);
        $mixte = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(array(
            'nomCategorie' => 'mixte'
        ), null, null);
        $actualite = $this->getDoctrine()->getRepository(Actualite::class)->recuperation();



        return $this->render("home/index.html.twig", [


            'styles' => $styles,
            'articles' => $articles,
            'homme' => $homme,
            'femme' => $femme,
            'enfant' => $enfant,
            'mixte' => $mixte,
            'marques' => $marques,
            'actualite' => $actualite

        ]);
    }



    /**
     * @Route("/change-locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);
        return $this->redirect($request->headers->get('referer'));
    }
}
