<?php

namespace App\Controller\Admin\recipe;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin.')]
class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    public function index(): Response
    {
        return $this->render('admin/recipe/recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
        ]);
    }
}
