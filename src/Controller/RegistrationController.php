<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UtilisateursAdresses;
use App\Form\RegistrationFormType;
use App\Form\UtilisateursAdressesType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);


        $form->handleRequest($request);
        $token = $tokenGenerator->generateToken();
        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setIsVerified(false)
                ->setActivationToken($token)
                ->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Nouveau compte'))
                // hna ana li rah nab3at
                ->setFrom('abdouschools@gmail.fr')
                // hna jabna laman rah naba3toh  hna jabna email ta user
                ->setTo($user->getEmail())
                //
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig',
                        ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi
            return $this->redirectToRoute('home');
            $this->addFlash('message', 'Please visite Your Email To Confirme Your Account'); // Permet un message flash de renvoi
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),

        ]);
    }
    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $users, TranslatorInterface  $translator)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $users->findOneBy(['activation_token' => $token]);
        $message = $translator->trans('page inaccesible');
        // Si aucun utilisateur n'est associé à ce token
        if (!$user) {
            // On renvoie une erreur 404
            throw $this->createNotFoundException($message);
        }

        // On supprime le token
        //on passe le champ is verified a true 
        $user->setActivationToken(null)
            ->setIsVerified(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        return $this->redirectToRoute('app_login');
    }
}
