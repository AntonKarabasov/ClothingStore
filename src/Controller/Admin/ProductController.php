<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Admin\EditProductFormType;
use App\Form\DTO\EditProductModel;
use App\Form\Handler\ProductFormHandler;
use App\Repository\ProductRepository;
use App\Utils\Manager\ProductManager;
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
     * @Route("/edit", name="edit_blank")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, ProductFormHandler $productFormHandler, Product $product = null): Response
    {
		$editProductModal = EditProductModel::makeFromProduct($product);

	    //Получим созданную форму для редактирования продуктов и передадим форме продукт
	    $form = $this->createForm(EditProductFormType::class, $editProductModal);
	    //Получим данные из запроса
	    $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processEditForm($editProductModal, $form);

			$this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

	    if ($form->isSubmitted() && !$form->isValid()) {
		    $this->addFlash('warning', 'Something went wrong. Please check your form!');
	    }

		$images = $product
			? $product->getProductImages()->getValues()
			: [];

        return $this->render('admin/product/edit.html.twig', [
			'images' => $images,
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Product $product, ProductManager $productManager): Response
    {
        $productManager->remove($product);

	    $this->addFlash('warning', 'The product was successfully deleted!');

		return $this->redirectToRoute('admin_product_list');
    }
}
