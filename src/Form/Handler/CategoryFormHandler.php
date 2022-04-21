<?php

namespace App\Form\Handler;

use App\Entity\Category;
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

	public function processEditForm(Category $category): void
	{
		$title = $category->getTitle();
		$category->setTitle($title);
		$this->categoryManager->save($category);
	}
}