<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/dashboard', name: 'app_profile_dashboard', methods: ['GET'])]
    public function dashboard(EventRepository $eventRepository) : Response
    {
        return $this->render('profile/dashboard.html.twig', [
            'events' => $eventRepository->upcomingEventsForUser($this->getUser()),
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository) : Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !$request->isXmlHttpRequest()) {
            $userRepository->save($user, true);

            $this->addFlash('alert.success', 'Profile updated!');

            return $this->redirectToRoute('app_profile_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event-history', name: 'app_profile_event_history', methods: ['GET'])]
    public function eventHistory(EventRepository $eventRepository) : Response
    {
        return $this->render('profile/event_history.html.twig', [
            'events' => $eventRepository->historyEventsForUser($this->getUser()),
        ]);
    }

    #[IsGranted('ROLE_ORGANIZER')]
    #[Route('/my-events', name: 'app_profile_my_events', methods: ['GET'])]
    public function myEvents(EventRepository $eventRepository) : Response
    {
        return $this->render('profile/my_events.html.twig', [
            'events' => $eventRepository->myEventsForOrganizer($this->getUser()),
        ]);
    }
}