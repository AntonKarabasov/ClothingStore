<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\EditProductFormType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product", name="admin_product_")
 */
class ProductController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function list(ProductRepository $productRepository): Response
    {
        //Получим продукты, которые не удалены и сделаеим сортировку по id
        $products = $productRepository->findBy(['isDeleted' => false], ['id' => 'DESC'], 50);

        return $this->render('admin/product/list.html.twig', [
          'products' => $products
          ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, Product $product = null): Response
    {
        //Получим созданную форму для редактирования продуктов и передадим форме продукт
        $form = $this->createForm(EditProductFormType::class, $product);
        //Получим данные из запроса
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig', [
          'product' => $product,
          'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(): Response
    {
        //
    }
}
