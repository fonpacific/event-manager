<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use function assert;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const WELCOME_ROUTE = 'welcome';

    public function __construct(private UrlGeneratorInterface $urlGenerator, private Security $security, private EntityManagerInterface $entityManager)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->getWelcomeUrl($request));
    }

    protected function getWelcomeUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::WELCOME_ROUTE);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    /** @return array|User[] */
    public function getImpersonableUsers(): array
    {
        if (! $this->security->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            return [];
        }

        $user = $this->security->getUser();
        if ($user === null) {
            return [];
        }

        assert($user instanceof User);

        /** @var User[] $users */
        $users = $this->entityManager->getRepository(User::class)->findBy([]);
        $collection = new ArrayCollection($users);

        return $collection
            ->filter(static fn (User $collectionUser) => $user->getId() !== $collectionUser->getId())
            ->toArray();
    }
}
