<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Notification\UserNotifier;
use App\Repository\UserDoesNotExist;
use App\Repository\UserEmailIsAlreadyConfirmed;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserNotifier
     */
    private $userNotifier;

    public function __construct(UserRepository $userRepository, UserNotifier $userNotifier)
    {
        $this->userRepository = $userRepository;
        $this->userNotifier = $userNotifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator): Response
    {
        // if authenticated
        if ($this->isGranted('ROLE_USER_NOT_CONFIRMED')) {
            return RedirectResponse::create('/');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->userRepository->register($user);
            $this->userNotifier->requestEmailConfirmation($user);

            $this->addFlash('success', 'You have been successfully registered. Confirm your email to use all of the features.');
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm-email/{confirmationToken}", name="app_confirm_email")
     */
    public function confirmEmailAddress(string $confirmationToken): Response
    {
        try {
            $this->userRepository->confirmUserEmail($confirmationToken);
            $user = $this->userRepository->findOneBy(['confirmationToken' => $confirmationToken]);
            $this->userNotifier->greetUser($user);
            $this->addFlash('success', 'Your email has been confirmed! Feel free to use all of the features.');
        } catch (UserDoesNotExist|UserEmailIsAlreadyConfirmed $e) {
            $this->addFlash('warning', $e->getMessage());
        }

        return $this->redirect('/');
    }
}
