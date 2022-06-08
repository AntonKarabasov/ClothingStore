<?php

namespace App\Controller\Main;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
    public function showLastProducts(ProductRepository $productRepository, int $productCount = 2, int $categoryId = null): Response
    {
		$params = [];

		if ($categoryId) {
			$params['category'] = $categoryId;
		}

		$products = $productRepository->findBy($params,  ['id' => 'DESC'], $productCount);

	    foreach ($products as $id => $product) {
		    if ($product->getIsDeleted() === true) {
			    unset($products[$id]);
		    }
	    }

        return $this->render('main/_embed/_similar_products.html.twig', [
            'products' => $products,
        ]);
    }
}
