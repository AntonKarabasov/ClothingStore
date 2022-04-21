<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class ProductFormHandler
{
	/**
	 * @var FileSaver
	 */
	private FileSaver $fileSaver;

	/**
	 * @var ProductManager
	 */
	private ProductManager $productManager;

	public function __construct(ProductManager $productManager, FileSaver $fileSaver)
	{

		$this->fileSaver = $fileSaver;
		$this->productManager = $productManager;
	}

	/**
	 * @param EditProductModel $editProductModal
	 * @param Form $form
	 *
	 * @return Product|null
	 */
	public function processEditForm(EditProductModel $editProductModal, Form $form): ?Product
	{
		// TODO: ADD A NEW IMAGE WITH DIFFERENT SIZES TO THE PRODUCT
		//0. Построим продукт перед сохранением
		$product = new Product();

		if ($editProductModal->id) {
			$product = $this->productManager->find($editProductModal->id);
		}

		$product->setTitle($editProductModal->title);
		$product->setPrice($editProductModal->price);
		$product->setQuantity($editProductModal->quantity);
		$product->setDescription($editProductModal->description);
		$product->setCategory($editProductModal->category);
		$product->setIsPublished($editProductModal->isPublished);
		$product->setIsDeleted($editProductModal->isDeleted);

		//1. Сохраненить изменений продукта
		$this->productManager->save($product);

		//2. Сохранить загруженный файл во временной папке
		$newImageFile = $form->get('newImage')->getData();

		$tempImageFileName = $newImageFile
			? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
			: null;

		//3. Провести работу с Product(добавить изображение) и ProductImage
		//3.1 Получить путь к папке с изображениеми продукта
		//3.2 Работа с ProductImage
		//3.2.1 Изменние размера и сохрание картике в папке (BIG, MIDDLE, SMALL)
		//3.2.2 Создание ProductImage и вернуть его Product
		$this->productManager->updateProductImages($product, $tempImageFileName);

		//3.3 Сохранить Product с ProductImage
		$this->productManager->save($product);

		return $product;
	}
}