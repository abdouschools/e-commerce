<?php

namespace App\Controller;

use App\Entity\Articles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Data\SearchData;
use App\Entity\Comments;
use App\Entity\Images;
use App\Entity\Marques;
use App\Form\ArticlesType;
use App\Form\SearchType;
use App\Repository\ArticlesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use App\Form\CommentsType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticlesController extends AbstractController
{


    /**
     * @Route("/article/{slug}", name="article")
     */
    public function show($slug, Request $request, Session $session, TranslatorInterface $translator)
    {
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $marques = $this->getDoctrine()->getRepository(Articles::class)->findBy(array('marques' =>  $article->getMarques()), array('marques' => 'ASC'), 6, 0);
        $message = $translator->trans("pas d'article avec ce titre");
        if (!$article) {
            throw $this->createNotFoundException($message);
        }
        if (!$marques) {
            throw $this->createNotFoundException($message);
        }

        if ($session->has('panier')) {
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }


        $comment = new Comments();
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setActicles($article);
            $user = $this->getUser();
            $comment->setUserComment($user);
            $parentid = $commentForm->get("parentid")->getData();




            $em = $this->getDoctrine()->getManager();

            if ($parentid != null)
                $parent = $em->getRepository(Comments::class)->find($parentid);
            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('message', 'commentaire envoyé');
            return $this->redirectToRoute('article', ['slug' => $article->getSlug()]);
        }

        return $this->render("articles/show.html.twig", [
            'marques' => $marques,
            'article' => $article,
            'panier' => $panier,
            'commentForm' => $commentForm->createView(),

        ]);
    }



    /**
     * @Route("/filter", name="filter")
     */
    public function filter(ArticlesRepository $repository, Request $request, TranslatorInterface  $translator, PaginatorInterface $paginator)
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $message = $translator->trans('Pas De Produits Avec Ces Critères');
        $article = $repository->findSearch($data);
        if ($article == null) {
            $this->addFlash('message',  $message);
        }
        $articles = $paginator->paginate(
            $article, //hna hatina les articles li jabnahom
            $request->query->getInt('page', 1), //hna hatina numero ta la page w 1 aw darnah la malgahach yhat 1 par default
            6   // hna ma3naha hatana 4 article par page
        );


        return $this->render('articles/search.html.twig', [
            'articles' => $articles,

            'form' => $form->createView()
        ]);
    }
}
