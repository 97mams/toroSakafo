<?php

namespace App\Controller\Admin\recipe;

use App\Entity\Recipe;
use App\Form\RecipeFormRequestType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin.')]
class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    public function index(RecipeRepository $repository): Response
    {
        $recipes = $repository->findAll();

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/ajout', name: 'recipe.create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $recipe = new Recipe();
        $formRecipe = $this->createForm(RecipeFormRequestType::class, $recipe);
        $formRecipe->handleRequest($request);
        if ($formRecipe->isSubmitted() && $formRecipe->isValid()) {
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            '$formRecipe' => $formRecipe
        ]);
    }

    #[Route('/recipe/edit/{id}', name: 'recipe.edit')]
    public function edit(Recipe $recipe, EntityManagerInterface $em, Request $request): Response
    {
        $formRecipe = $this->createForm(RecipeFormRequestType::class, $recipe);
        $formRecipe->handleRequest($request);
        if ($formRecipe->isSubmitted() && $formRecipe->isValid()) {
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/recipe/edit.html.twig', [
            '$form' => $formRecipe,
        ]);
    }

    #[Route('/recipe/suppression', name: 'recipe.delete')]
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();

        return $this->redirectToRoute('admin.recipe.index');
    }
}
