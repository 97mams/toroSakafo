<?php

namespace App\Controller\Admin\category;

use App\Entity\Category;
use App\Form\CategoryFormRequestType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin.')]
class CategoryController extends AbstractController
{

    #[Route('/categorie', name: 'category.index')]
    public function index(CategoryRepository $repository): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $repository->findAll()
        ]);
    }

    #[Route('/categorie/ajout', name: 'category.create')]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $formCategory = $this->createForm(CategoryFormRequestType::class, $category);
        $formCategory->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/categories/create.html.twig', [
            'formCategory' => $formCategory
        ]);
    }

    #[Route('/categorie/edition/{id}', name: 'category.edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $formCategory = $this->createForm(CategoryFormRequestType::class, $category);
        $formCategory->handleRequest($request);
        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/categories/edit.html.twig');
    }

    #[Route('/categorie/suppression/{id}', name: 'category.delete')]
    public function delete(Category $category, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('admin.category.index');
    }
}
