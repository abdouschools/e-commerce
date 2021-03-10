<?php

namespace App\Controller;

use App\Entity\PrincipaleAdresse;
use App\Form\PrincipaleAdresseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PrincipaleAdresseController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/principale/adresse", name="principale_adresse")
     */
    public function ajoute(Request $request)
    {
        $adresse = new PrincipaleAdresse();

        $form = $this->createForm(PrincipaleAdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUserAdresse($this->getUser());
            $em = $this->getDoctrine()->getManager();

            $em->persist($adresse);
            $em->flush();
            $this->addFlash('message', 'adresse ajouté avec succes');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('principale_adresse/ajoute_adresse.html.twig', [

            'form' => $form->createView()
        ]);
    }
}
