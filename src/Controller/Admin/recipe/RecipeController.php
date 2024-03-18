<?php

namespace App\Controller\Admin\recipe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/admin/recipe/recipe', name: 'app_admin_recipe_recipe')]
    public function index(): Response
    {
        return $this->render('admin/recipe/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
        ]);
    }
}
