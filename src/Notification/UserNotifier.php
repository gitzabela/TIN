<?php

namespace App\Notification;

use App\Entity\User;
use Twig\Environment;

class UserNotifier
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twigEnvironment;

    public function __construct(\Swift_Mailer $mailer, Environment $twigEnvironment)
    {
        $this->mailer = $mailer;
        $this->twigEnvironment = $twigEnvironment;
    }
    
    public function requestEmailConfirmation(User $user): void
    {
        $message = new \Swift_Message('Hello email');
        $message->setFrom('no-reply@projectname.com')
            ->setTo($user->getEmail())
            ->setBody($this->twigEnvironment->render('email/confirmation.html.twig', ['user' => $user]));

        $this->mailer->send($message);
    }
}
