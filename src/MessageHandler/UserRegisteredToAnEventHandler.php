<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserRegisteredToAnEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
class UserRegisteredToAnEventHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(UserRegisteredToAnEvent $userRegisteredToAnEvent): void
    {
        $email = (new TemplatedEmail())
            ->from('fabien@example.com')
            ->to(new Address('ryan@example.com'))
            ->subject('Thanks for signing up!')

            // path of the Twig template to render
            ->htmlTemplate('emails/signupToAnEvent.html.twig');
        $this->mailer->send($email);
    }
}
