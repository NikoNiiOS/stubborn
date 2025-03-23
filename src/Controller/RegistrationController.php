<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    //#[OA\Post(
        //path: '/register',
        //tags: ['User'],
        //summary: 'Enregistrer un nouvel utilisateur',
        //description: 'Permet à un utilisateur de créer un nouveau compte en fournissant les informations nécessaires.',
        //requestBody: new OA\RequestBody(
            //content: new OA\JsonContent(
                //type: 'object',
                //properties: [
                    //new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    //new OA\Property(property: 'plainPassword', type: 'string', format: 'password', example: 'password')
                //],
                //required: ['email', 'plainPassword']
            //)
        //),
        //responses: [
            //new OA\Response(
                //response: 201,
                //description: 'Utilisateur créé avec succès',
                //content: new OA\JsonContent(
                    //type: 'object',
                    //properties: [
                        //new OA\Property(property: 'message', type: 'string', example: 'Votre compte a bien été créé, merci de vérifier vos emails pour l\'activer')
                    //]
                //)
            //),
            //new OA\Response(
                //response: 400,
                //description: 'Données invalides'
            //)
        //]
    //)]    
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a bien été créé, merci de vérifier vos emails pour l\'activer');

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('stubborn@mail.com', 'Stubborn'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }
        
        $this->addFlash('success', 'Votre mail a bien été vérifié.');

        return $this->redirectToRoute('home');
    }
}
