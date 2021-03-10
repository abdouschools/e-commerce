<?php

namespace App\Controller;

use App\Entity\Actualite;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActualiteController extends AbstractController
{

    /**
     * @Route("/actualites", name="actualites")
     */
    public function showAll(Request $request, PaginatorInterface $paginator, TranslatorInterface $translator)
    {
        $actualite = $this->getDoctrine()->getRepository(Actualite::class)->findAll();
        $message = $translator->trans("désolé pas d'article pour le moment");
        if (!$actualite) {
            throw $this->createNotFoundException($message);
        }
        $articles = $paginator->paginate(
            $actualite, //hna hatina les articles li jabnahom
            $request->query->getInt('page', 1), //hna hatina numero ta la page w 1 aw darnah la malgahach yhat 1 par default
            8   // hna ma3naha hatana 4 article par page
        );
        return $this->render("actualite/showall.html.twig", [
            'actualite' => $articles,

        ]);
    }
    /**
     * @Route("/actualite-show/{slug}", name="actualite-show")
     */
    public function show($slug, TranslatorInterface $translator)
    {
        $message = $translator->trans("pas d'article avec ce titre");

        $actualite = $this->getDoctrine()->getRepository(Actualite::class)->findOneBy(['slug' => $slug]);
        if (!$actualite) {
            throw $this->createNotFoundException($message);
        }
        return $this->render('actualite/show.html.twig', [
            'actualite' => $actualite
        ]);
    }
}
