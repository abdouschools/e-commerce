<?php

namespace App\Controller;


use App\Entity\Commandes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PrincipaleAdresse;
use App\Repository\ArticlesRepository;
use App\Repository\UtilisateursAdressesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/** 
 * @IsGranted("ROLE_USER")
 */
class CommandesController extends AbstractController
{



    public function facture(Session $session, UtilisateursAdressesRepository $utilisateursAdressesRepository, ArticlesRepository $articlesRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(PrincipaleAdresse::class);

        $adresse = $session->get('adresse');
        $panier = $session->get('panier');
        $commande = array();
        $totalHT = 0;
        $totalTVA = 0;
        $facturationP =  $repository->find($adresse['facturation']);
        $livraisonP = $repository->find($adresse['livraison']);
        $facturation = $utilisateursAdressesRepository->find($adresse['facturation']);
        $livraison = $utilisateursAdressesRepository->find($adresse['livraison']);
        $produits = array();
        foreach (array_keys($session->get('panier')) as $prod) {
            $produits[] = $articlesRepository->find($prod);
        };
        foreach ($produits as $produit) {
            $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
            $prixTTC = ($produit->getPrix() * $panier[$produit->getId()] / $produit->getTvaMultiplication());
            $totalHT +=  $prixHT;

            if (!isset($commande['tva']['%' . $produit->getValeur()])) {
                $commande['tva']['%' . $produit->getValeur()] = round($prixTTC - $prixHT, 2);
            } else {
                $commande['tva']['%' . $produit->getValeur()] += round($prixTTC - $prixHT, 2);
            }
            $totalTVA += round($prixTTC - $prixHT, 2);

            $commande['produit'][$produit->getId()] = array(

                'reference' => $produit->getNom(),
                'quantite' => $panier[$produit->getId()],
                'prixHT' => round($produit->getPrix(), 2),
                'prixTTC' => round($produit->getPrix() / $produit->getTvaMultiplication(), 2)



            );
        }

        if ($livraison !== null) {
            $commande['livraison'] = array(
                'prenom' => $livraison->getPrenom(),
                'nom' => $livraison->getNom(),
                'telephone' => $livraison->getTelephone(),
                'cp' => $livraison->getCp(),
                'vilee' => $livraison->getVille(),
                'pays' => $livraison->getPays(),
                'complement' => $livraison->getComplement(),
                'adresse' => $livraison->getAdresse()
            );
        } else {
            $commande['livraison'] = array(
                'prenom' =>  $livraisonP->getPrenom(),
                'nom' => $livraisonP->getNom(),
                'telephone' => $livraisonP->getTelephone(),
                'cp' => $livraisonP->getCp(),
                'vilee' => $livraisonP->getVille(),
                'pays' => $livraisonP->getPays(),
                'complement' => $livraisonP->getComplement(),
                'adresse' => $livraisonP->getAdresse()
            );
        };
        if ($facturation !== null) {
            $commande['facturation'] = array(
                'prenom' => $facturation->getPrenom(),
                'nom' => $facturation->getNom(),
                'telephone' => $facturation->getTelephone(),
                'cp' => $facturation->getCp(),
                'vilee' => $facturation->getVille(),
                'pays' => $facturation->getPays(),
                'complement' => $facturation->getComplement(),
                'adresse' => $facturation->getAdresse()
            );
        } else {
            $commande['facturation'] = array(
                'prenom' => $facturationP->getPrenom(),
                'nom' => $facturationP->getNom(),
                'telephone' => $facturationP->getTelephone(),
                'cp' => $facturationP->getCp(),
                'vilee' => $facturationP->getVille(),
                'pays' => $facturationP->getPays(),
                'complement' => $facturationP->getComplement(),
                'adresse' => $facturationP->getAdresse()
            );
        }

        $commande['prixHt'] = round($totalHT, 2);
        $commande['prixTTC'] = round($totalHT + $totalTVA, 2);

        return $commande;
    }

    public function prepareCommandeAction(Session $session, UtilisateursAdressesRepository $utilisateursAdressesRepository, ArticlesRepository $articlesRepository)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$session->has('commande')) {
            $commande = new Commandes();
        } else {
            $commande = $em->getRepository(Commandes::class)->find($session->get('commande'));
        }
        $commande->setUtilisateur($this->getUser());
        $commande->setValider(0);
        $commande->setDate(new \DateTime());
        $commande->setReference(0);
        $commande->setCommande($this->facture($session, $utilisateursAdressesRepository, $articlesRepository));

        if (!$session->has('commande')) {
            $em->persist($commande);
            $session->set('commande', $commande);
        }

        $em->flush();
        return new Response($commande->getId());
    }



    public function reference()
    {
        $em = $this->getDoctrine()->getManager();
        $reference = $em->getRepository(Commandes::class)->findOneBy(array('valider' => 1), array('id' => 'DESC'), 1, 1);
        if (!$reference) {
            return 1;
        } else {
            return $reference->getReference() + 1;
        }
    }
    /**
     * @Route("/finalisation/{id<\d+>}", name="finalisation")
     */
    public function validationCommande($id, Session $session)
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commandes::class)->find($id);
        if (!$commande || $commande->getValider() == 1) {
            throw $this->createNotFoundException("la commande Ñ'existe pas");
        }
        $commande->setValider(1);
        $commande->setReference($this->reference());

        $session->remove('adresse');
        $session->remove('panier');
        $session->remove('commande');
        $em->persist($commande);
        $em->flush();

        $this->addFlash('success', 'Votre commande est validé avec succes');
        return $this->redirectToRoute('facture');
    }
    /**
     * @Route("/suprime-commande{id<\d+>}", name="commande-supprime")
     */
    public function suprime($id, TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commandes::class)->find($id);
        $message = $translator->trans("la commande Ñ'existe pas");
        if (!$commande) {
            throw $this->createNotFoundException($message);
        }
        $em->remove($commande);
        $em->flush();
        return $this->redirectToRoute('facture');
    }
}
