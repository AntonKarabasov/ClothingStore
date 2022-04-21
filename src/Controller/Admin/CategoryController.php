<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\DTO\EditCategoryModal;
use App\Form\EditCategoryFormType;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
	/**
	 * @Route("/list", name="list")
	 */
    public function list(CategoryRepository $categoryRepository): Response
    {
	    $categories = $categoryRepository->findBy(['isDeleted' => false, 'isDeleted' => null], ['id' => 'DESC']);

	    return $this->render('admin/category/list.html.twig', [
		    'categories' => $categories
	    ]);
    }

	/**
	 * @Route("/edit/{id}", name="edit")
	 * @Route("/add", name="add")
	 */
	public function edit(Request $request, CategoryFormHandler $categoryFormHandler, Category $category = null): Response
	{
		$editCategoryModal = EditCategoryModal::makeFromCategory($category);

		$form = $this->createForm(EditCategoryFormType::class, $editCategoryModal);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$category = $categoryFormHandler->processEditForm($editCategoryModal);

			return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
		}

		return $this->render('admin/category/edit.html.twig', [
			'category' => $category,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/delete/{id}", name="delete")
	 */
	public function delete(Category $category, CategoryManager $categoryManager): Response
	{
		$categoryManager->remove($category);

		return $this->redirectToRoute('admin_category_list');
	}
}
