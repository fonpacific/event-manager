<?php

namespace App\Controller;

use App\Entity\User;
use App\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(
        UserPasswordHasherInterface $userPasswordHasher,
        EventManager $eventManager,
        EntityManagerInterface $manager,
        ): Response
    {
        $adminUser=new User();
        $adminUser->setEmail('admin@eventmanager.com');
        $adminUser->setPassword($userPasswordHasher->hashPassword(
            $adminUser,'4dmin123'
        ));
        $adminUser->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($adminUser);
        $organizers[] = $adminUser;

        $manager->flush();
        
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
