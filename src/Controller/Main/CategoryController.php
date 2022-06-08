<?php

namespace App\Controller\Main;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="main_category_show")
     */
    public function show(Category $category): Response
    {
		if (!$category) {
			throw new NotFoundHttpException();
		}

		$products = $category->getProducts()->getValues();

	    foreach ($products as $id => $product) {
			if ($product->getIsDeleted() === true) {
				unset($products[$id]);
			}
		}


        return $this->render('main/category/show.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
