<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\EditProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", methods="GET", name="homepage")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productList = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('main/default/index.html.twig', []);
    }


    /**
     * @Route("/edit-product/{id}", methods="GET|POST", name="product_edit", requirements={"id"="\d+"})
     * @Route("/add-product", methods="GET|POST", name="product_add")
     */
    public function editProduct(Request $request, int $id = null): Response
    {
        // Получем менеджер сущностей
        $entityManager = $this->getDoctrine()->getManager();

        if ($id) {
            // Если id существет, то достаем объект из БД
            $product = $entityManager->getRepository(Product::class)->find($id);
        } else {
            // Если id нет, то создаем новый объект
            $product = new Product();
        }

        // Создаём форму и передаем ей объект
        $form = $this->createForm(EditProductFormType::class, $product);

        // Передадим данные из запроса в форму
        $form->handleRequest($request);

        // Проверим была ли отправлена форма и что в ней валидные данные
        if ($form->isSubmitted() && $form->isValid()) {

            //Обновляем данные в БД
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        // Рендарим шаблон для формы и передаем ему форму
        return $this->render('main/default/edit_product.html.twig', [
          'form' => $form->createView()
        ]);
    }
}
