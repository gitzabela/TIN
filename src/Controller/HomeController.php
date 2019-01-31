<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function indexAction(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('home/dashboard.html.twig', [
            'categories' => $this->categoryRepository->findAllMainCategoriesWithCounts()
        ]);
    }
}
