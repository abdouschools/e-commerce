<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commandes;
use App\Entity\UtilisateursAdresses;
use App\Form\UtilisateursAdressesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class PanierController extends AbstractController
{

    /**
     * @Route("/ajouter/{id<\d+>}", name="ajouter")
     */
    public function ajouter($id, SessionInterface $session, Request $request, TranslatorInterface  $translator)
    {
        if (!$session->has('panier')) $session->set('panier', array());
        $panier = $session->get('panier');
        if (array_key_exists($id, $panier)) {
            if ($request->get('qte') != null) $panier[$id] = $request->get('qte');
            $message = $translator->trans('Article ajouté');
            $this->addFlash('message', $message);
        } else {
            if ($request->get('qte') != null) $panier[$id] = $request->get('qte');
            else
                $panier[$id] = 1;
        }
        $session->set('panier', $panier);
        $this->addFlash('message', 'Article ajouté');

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panierAction(SessionInterface $session)
    {
        if (!$session->has('panier')) $session->set('panier', array());
        $em = $this->getDoctrine()->getManager();
        $panier = $session->get('panier');
        $totalHT = 0;
        $totalTVA = 0;
        $commande = array();
        $articles = $em->getRepository(Articles::class)->findArray(array_keys($session->get('panier')));


        foreach ($articles as $produit) {
            $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
            $prixTTC = ($produit->getPrix() * $panier[$produit->getId()] / $produit->getTvaMultiplication());
            $totalHT +=  $prixHT;

            if (!isset($commande['tva']['%' . $produit->getValeur()])) {
                $commande['tva']['%' . $produit->getValeur()] = round($prixTTC - $prixHT, 2);
            } else {
                $commande['tva']['%' . $produit->getValeur()] += round($prixTTC - $prixHT, 2);
            }
            $totalTVA += round($prixTTC - $prixHT, 2);
        };
        $commande['totalHT'] =  round($totalHT, 2);
        $commande['totalTTC'] = round($totalHT + $totalTVA, 2);


        return $this->render('panier/index.html.twig', [
            'articles' => $articles,
            'panier' => $session->get('panier'),
            'commande' => $commande

        ]);
    }

    /**
     * @Route("/suprime/{id<\d+>}", name="supprime")
     */
    public function suprime($id, SessionInterface $session)
    {
        $panier = $session->get('panier');
        if (array_key_exists($id, $panier)) {
            unset($panier[$id]);
            $session->set('panier', $panier);
            $this->addFlash('message', 'suceesssssssss');
        }


        return $this->redirectToRoute('panier');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/livraison", name="livraison")
     */
    public function livraison(Request $request)
    {
        $adresse = new UtilisateursAdresses();
        $utilisateur = $this->getUser();


        $form = $this->createForm(UtilisateursAdressesType::class, $adresse);

        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            if ($form->isValid() && $form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $adresse->setUtilisateur($utilisateur);
                $em->persist($adresse);
                $em->flush();
                return $this->redirectToRoute('livraison');
            }
        }
        return $this->render('panier/livraison.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/livraisonAdresseSuppresion/{id<\d+>}", name="livraisonAdresseSuppresion")
     */
    public function livraisonAdresseSuppresion($id)
    {
        $em = $this->getDoctrine()->getManager();
        $adresse = $em->getRepository(UtilisateursAdresses::class)->find($id);

        if (!$adresse) {
            $this->addFlash('message', 'adresse non trouver');
        }
        $em->remove($adresse);
        $em->flush();
        return $this->redirectToRoute('livraison');
    }


    public function setLiavraisonOnSession(Session $session, Request $request)
    {

        if (!$session->has('adresse')) $session->set('adresse', array());
        $adresse = $session->get('adresse');


        if ($request->get('livraison') != null && $request->get('facturation') != null) {
            $adresse['livraison'] = $request->get('livraison');
            $adresse['facturation'] = $request->get('facturation');
        } else {

            return $this->redirectToRoute('validation');
        }
        $session->set('adresse', $adresse);

        return $this->redirectToRoute('livraison');
    }



    /**
     * @IsGranted("ROLE_USER")
     * @Route("/validation", name="validation")
     */
    public function validation(Request $request, Session $session, TranslatorInterface  $translator)
    {

        if ($request->isMethod('POST'))

            $this->setLiavraisonOnSession($session, $request);

        $em = $this->getDoctrine()->getManager();
        $prepareCommande = $this->forward('App\Controller\CommandesController::prepareCommandeAction');
        $commande = $em->getRepository(Commandes::class)->find($prepareCommande->getContent());

        $message = $translator->trans('adresse non trouver');
        if (!$commande) {
            throw $this->createNotFoundException($message);
        }



        return $this->render('panier/validation.html.twig', array('commande' => $commande));
    }

    public function artPanier(SessionInterface $session)
    {
        if (!$session->has('panier')) {
            $article = 0;
        } else {
            $article = count($session->get('panier'));
        }

        return $this->render('panier/countPanier.html.twig', [
            'article' => $article,
        ]);
    }
}
