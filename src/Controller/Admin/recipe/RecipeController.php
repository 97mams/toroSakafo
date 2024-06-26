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
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin', name: 'admin.')]
class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    public function index(RecipeRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 8;
        $recipes = $repository->paginateRecipe($page, $limit);

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
            'formRecipe' => $formRecipe
        ]);
    }

    #[Route('/recipe/edit/{id}', name: 'recipe.edit')]
    public function edit(Recipe $recipe, EntityManagerInterface $em, Request $request): Response
    {
        $formRecipe = $this->createForm(RecipeFormRequestType::class, $recipe);
        $formRecipe->handleRequest($request);
        if ($formRecipe->isSubmitted() && $formRecipe->isValid()) {
            /** @var UploadedFile $file */
            $file = $formRecipe->get('thumbnailFile')->getData();
            $fileName =  $recipe->getId() . '.' . $file->getClientOriginalExtension();
            $file->move($this->getParameter('kernel.project_dir') . '/public/recette/Images', $fileName);
            $recipe->setThumbnail($fileName);
            $em->flush();

            toastr()->addSuccess('votre recette a été bien éditer');
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'form' => $formRecipe,
        ]);
    }

    #[Route('/recipe/suppression/{id}', name: 'recipe.delete')]
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();

        $this->addFlash('success', 'la recette a été bien supprimer');

        return $this->redirectToRoute('admin.recipe.index');
    }

    #[Route('/recipe/{slug}-{id}', name: 'recipe.show', methods: 'GET', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);
        return $this->render('admin/recipe/show.html.twig', compact('recipe'));
    }
}
