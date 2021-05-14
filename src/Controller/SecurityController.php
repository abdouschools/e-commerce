<?php

namespace App\Controller;

use Dompdf\Dompdf;

use Dompdf\Options;
use App\Entity\User;
use App\Entity\Commandes;
use App\Form\ResetPassType;
use App\Form\PromtAdminType;
use App\Form\EditProfileType;
use App\Entity\ChangePassword;
use App\Form\ChangePasswordType;
use App\Form\UtilisateursAdressesType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request, UserRepository $user, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        //creer le form 
        $form = $this->createForm(ResetPassType::class);
        //traitement 
        $form->handleRequest($request);
        //voire si le formulaire et valid 
        if ($form->isSubmitted() && $form->isValid()) {
            $donnes = $form->getData();
            //nchofo ida kayan utilisateur ando had email
            $user = $user->findOneByEmail($donnes['email']);
            //si le user nexiste pas
            if (!$user) {
                $this->addFlash("danger", "Cette Adresse ñ'existe Pas");
                $this->redirectToRoute('app_login');
            }
            //hna doka ngeniriw un token 
            $token = $tokenGenerator->generateToken();
            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une Erreur Est Survenue :' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }
            //hna doka ngeniriw un url de renitialisation de mot de pass
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            //hna doka naba3to le message 
            $message = (new \Swift_Message('Nouveau compte'))
                // hna ana li rah nab3at
                ->setFrom('abdouschools@gmail.fr')
                // hna jabna laman rah naba3toh  hna jabna email ta user
                ->setTo($user->getEmail())
                //
                ->setBody(
                    "Bonjour une demande de réinitialisation de mot de passe a été faite pour le site lunetteriegriffa.fr veuillez cliquer sur le lien pour réinitialiser le mot de passe: " . $url
                );
            //en envoie lemail
            $mailer->send($message);
            //on ecrie le message flassh
            $this->addFlash('message', '
            un e-mail de réinitialisation du mot de passe a été envoyé à votre adresse e-mail');
            $this->redirectToRoute('app_login');
        }
        //hna naba3toh vers la page de envoie  deamail
        return $this->render('security/forgotten_password.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //chercher lutilisateur avec le token fournie 
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
        if (!$user) {
            $this->addFlash('warning', 'token inconnu');
            return $this->redirectToRoute('app_login');
        }
        if ($request->isMethod('POST')) {
            $user->setResetToken(null);
            //hna nchifriw le modpass
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('message', 'Changement de mot de passe réussi');
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile", name="app_profile")
     */
    public function profile()
    {
        return $this->render('profile/profile.html.twig');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/modif-profile", name="app_modif_profile")
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);



        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/modif_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    /** 
     * @IsGranted("ROLE_USER")
     * @Route("/profile/pass/modifie", name="profile_pass_modifie")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            if ($request->request->get('pass1') == $request->get('password')) {
                // On vérifie si les 2 mots de passe sont identiques
                if ($request->request->get('pass') == $request->request->get('pass2')) {
                    $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                    $em->flush();
                    $this->addFlash('message', 'Changement de mot de passe réussi');

                    return $this->redirectToRoute('app_profile');
                } else {
                    $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
                }
            } else {
                $this->addFlash('error', 'ancien mot de pass non valide');
            }
        }
        return $this->render('profile/modif_motpass.html.twig');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/pass/modifier", name="profile_pass_modifier")
     */

    public function updateIdentifiant(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) edit users
        $em = $this->getDoctrine()->getManager();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $user = $this->getUser();



        $change = new ChangePassword();

        $form = $this->createForm(ChangePasswordType::class, $change);

        // 2) handle the submit (will only happen on POST) La barre de debug Symfony me dit bien que c'est du post
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { //Là ça rentre pas



            $password = $passwordEncoder->encodePassword($user, $change->getPlainPassword());

            $user->setPassword($password);

            $em->persist($user);
            $em->flush();
            $this->addFlash('message', 'Changement de mot de passe réussi');
            return $this->redirectToRoute('app_profile');
        } //fin de if submitted*/

        return $this->render('profile/modif_motpass.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/facture", name="facture")
     */

    public function factureAction()
    {

        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository(Commandes::class)->byFacture($this->getUser());
        return $this->render('profile/facture.html.twig', [
            'facture' => $facture
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/facturepdf/{id}", name="facturepdf")
     */

    public function facturePdf($id)
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository(Commandes::class)->findOneBy(array(
            'utilisateur' => $this->getUser(),
            'valider' => 1,
            'id' => $id

        ));
        if (!$facture) {
            $this->addFlash('error', 'une erreur est survenue');
            return $this->generateUrl('facture');
        }


        // Retrieve the HTML generated in our twig file
        $html =  $this->renderView('profile/facturepdf.html.twig', [
            'facture' => $facture,
        ]);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $fichier = 'Facture' . $this->getUser()->getId() . '.pdf';
        // Output the generated PDF to Browser (force download)
        $dompdf->stream($fichier, [
            'Attachment' => true

        ]);
        return new Response();
    }
}
