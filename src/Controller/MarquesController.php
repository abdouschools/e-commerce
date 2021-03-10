<?php

namespace App\Controller;

use App\Entity\Marques;
use App\Form\MarquesType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MarquesController extends AbstractController
{



    /**
     * @Route("/marque-show/{id<\d+>}", name="marque-show")
     */
    public function show($id, TranslatorInterface  $translator, PaginatorInterface $paginator, Request $request)
    {
        $message = $translator->trans('pas de  style avec cette recherche');

        $marques = $this->getDoctrine()->getRepository(Marques::class)->find($id);
        $article = $marques->getArticle();
        $articles = $paginator->paginate(
            $article, //hna hatina les articles li jabnahom
            $request->query->getInt('page', 1), //hna hatina numero ta la page w 1 aw darnah la malgahach yhat 1 par default
            2 // hna ma3naha hatana 4 article par page
        );
        if (!$marques) {
            throw $this->createNotFoundException($message);
        }


        return $this->render('marques/show.html.twig', [
            'marques' => $marques,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/marques", name="marques")
     */
    public function showAll(TranslatorInterface  $translator)
    {
        $marques = $this->getDoctrine()->getRepository(Marques::class)->findAll();
        $message = $translator->trans('désole pas de  style pour le moment ');
        if (!$marques) {
            throw $this->createNotFoundException($message);
        }

        return $this->render("marques/showall.html.twig", [
            'marques' => $marques,


        ]);
    }

    public function showAlll(TranslatorInterface  $translator)
    {
        $marques = $this->getDoctrine()->getRepository(Marques::class)->findAll();
        $message = $translator->trans('sorry no style for the moment');
        if (!$marques) {
            throw $this->createNotFoundException($message);
        }

        return $this->render("marques/showalll.html.twig", [
            'marques' => $marques,



        ]);
    }
}
