<?php

namespace App\Form\Handler;

use App\Entity\Category;
use App\Form\DTO\EditCategoryModal;
use App\Utils\Manager\CategoryManager;

class CategoryFormHandler
{

	/**
	 * @var CategoryManager
	 */
	private CategoryManager $categoryManager;

	public function __construct(CategoryManager $categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}

	/**
	 * @param EditCategoryModal $editCategoryModal
	 *
	 * @return Category
	 */
	public function processEditForm(EditCategoryModal $editCategoryModal): Category
	{
		$category = new Category();

		if ($editCategoryModal->id) {
			$category = $this->categoryManager->find($editCategoryModal->id);
		}

		$category->setTitle($editCategoryModal->title);

		$this->categoryManager->save($category);

		return $category;
	}
}