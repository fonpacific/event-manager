<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileCompletionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private ValidatorInterface $validator,
        private RequestStack $requestStack,
        private Security $security
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $token = $this->tokenStorage->getToken();

        if($token === null) {
            return;
        }

        $user = $token->getUser();

        if($user === null) {
            return;
        }

        assert($user instanceof User);

        $errors = $this->validator->validate(value: $user, groups: ['Default','edit']);

        if(count($errors) === 0 || $this->security->isGranted('ROLE_ADMIN', $user)) {
            return;
        }

        $session = $this->requestStack->getMainRequest()->getSession();
        assert($session instanceof Session);

        if(count($session->getFlashBag()->peek('alert.profile_complete')) === 0)
        {
            $session->getFlashBag()->add('alert.profile_complete', 'HEI!!! COMPLETA IL PROFILO!!!');
        }
    }
}