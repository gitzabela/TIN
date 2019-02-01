<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        EventRepository $eventRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->eventRepository = $eventRepository;
    }

    public function indexAction($category = null): Response
    {
        return $this->render('home/dashboard.html.twig', [
            'categories' => $this->categoryRepository->findAllMainCategoriesWithCounts(),
            'events' => $this->eventRepository->findAllEventsByCategory($category)
        ]);
    }


    public function eventAction(Event $event): Response
    {
        return $this->render('home/event.html.twig', [
            'event' => $event
        ]);
    }
}
