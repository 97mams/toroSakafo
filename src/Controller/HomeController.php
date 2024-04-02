<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index(Request $request, RecipeRepository $repository): Response
    {
        $page = (int)$request->get('page', 1);
        $recipes = $repository->paginateRecipe($page, 9);
        return $this->render('home/index.html.twig', [
            'recipes' => $recipes
        ]);
    }
}
