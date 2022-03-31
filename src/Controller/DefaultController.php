<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('main/default/index.html.twig');
    }

    /**
     * @Route("/product-add", name="homepage")
     */
    public function productAdd(): Response
    {
        $product = new Product();

        return $this->render('main/default/index.html.twig');
    }
}
