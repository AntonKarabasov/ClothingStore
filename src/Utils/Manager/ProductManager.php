<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductManager
{
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $entityManager;

	/**
	 * @var string
	 */
	private string $productImagesDir;

	/**
	 * @var ProductImageManager
	 */
	private ProductImageManager $productImageManager;

	public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager,string $productImagesDir)
	{
		$this->entityManager = $entityManager;
		$this->productImagesDir = $productImagesDir;
		$this->productImageManager = $productImageManager;
	}

	/**
	 * @return ObjectRepository
	 */
	public function getRepository(): ObjectRepository
	{
		return $this->entityManager->getRepository(Product::class);
	}

	/**
	 * @param Product $product
	 *
	 * @return void
	 */
	public function save(Product $product): void
	{
		$this->entityManager->persist($product);
		$this->entityManager->flush();
	}

	public function remove()
	{
		//
	}

	/**
	 * @param Product $product
	 *
	 * @return string
	 */
	public function getProductImageDir(Product $product): string
	{
		return sprintf('%s/%s', $this->productImagesDir, $product->getId());
	}


	public function updateProductImages(Product $product, string $tempImageFilename = null): Product
	{
		if (!$tempImageFilename) {
			return $product;
		}

		$productDir = $this->getProductImageDir($product);

		$productImage = $this->productImageManager->saveImageForProduct($productDir, $tempImageFilename);
		$productImage->setProduct($product);
		$product->addProductImage($productImage);

		return $product;
	}
}