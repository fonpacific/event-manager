<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\EventManager;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Exception\UserIsAlreadyRegisteredThisEventException;
use App\Message\UserRegisteredToAnEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event_index', methods: ['GET'])]
    #[Route('/welcome', name: 'welcome', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAvailable(),
        ]);
    }

    #[Route('/event-search','app_event_search', methods:['GET'])]
    public function search(): Response {
        return $this->render('event/search.html.twig');
    }

    
    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $event->setStartDate(new \DateTime());
        $event->setEndDate(new \DateTime());
       

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/event/{slug}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[IsGranted('ROLE_ORGANIZER')]
    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $this->denyAccessUnlessGranted('edit', $event);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('event/{id}/register', name: 'app_event_register', methods: ['GET'])]
    public function register(Event $event, EventManager $eventManager, MessageBusInterface $messageBus): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);

        $messageBus->dispatch(new UserRegisteredToAnEvent($user->getId(), $event->getId()));

        try{
            $eventManager->register($event,$user);
            $this -> addFlash('alert.success', 'Registration successful');
        } catch (UserIsAlreadyRegisteredThisEventException $e) {
            $this -> addFlash('alert.danger', 'You are already registered');
        }
        
        
        return $this->redirectToRoute('app_event_show', ["slug" => $event->getSlug() ],Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('event/{id}/unregister', name: 'app_event_unregister', methods: ['GET'])]
    public function unregister(Event $event, EventManager $eventManager): Response
    {
        $user = $this->getUser();
        assert($user instanceof User);
        
        $eventManager->unregister($event,$user);
        

        return $this->redirectToRoute('welcome', [],Response::HTTP_SEE_OTHER);
    }

    
}
