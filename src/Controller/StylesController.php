<?php

namespace App\Controller;

use App\Entity\Styles;
use App\Form\StylesType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class StylesController extends AbstractController
{

    /**
     * @Route("/style-show/{id<\d+>}", name="style-show")
     */
    public function show($id, TranslatorInterface  $translator, PaginatorInterface $paginator, Request $request)
    {
        $message = $translator->trans('pas de style avec ce nom');

        $style = $this->getDoctrine()->getRepository(Styles::class)->find($id);
        if (!$style) {
            throw $this->createNotFoundException($message);
        }
        $article =  $style->getArticles();
        $articles = $paginator->paginate(
            $article, //hna hatina les articles li jabnahom
            $request->query->getInt('page', 1), //hna hatina numero ta la page w 1 aw darnah la malgahach yhat 1 par default
            4 // hna ma3naha hatana 4 article par page
        );
        return $this->render('styles/show.html.twig', [
            'style' => $style,
            'articles' => $articles
        ]);
    }
}
