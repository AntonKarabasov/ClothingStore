<?php

namespace App\Utils\File;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageResizer
{

	/**
	 * @var Imagine
	 */
	private $imagine;

	public function __construct()
	{
		$this->imagine = new Imagine();
	}

	/**
	 * @param string $originalFileFolder
	 * @param string $originalFilename
	 * @param array  $targetParams
	 *
	 * @return string
	 */
	public function resizeImageAndSave(string $originalFileFolder, string $originalFilename, array $targetParams): string
	{
		//Создадим полный путь для сохранения вместе с названием
		$originalFilePath = $originalFileFolder. '/' . $originalFilename;

		//Получим ширину и высоту изображения
		[$imageWidth, $imageHeight] = getimagesize($originalFilePath);

		//Вычислим соотношение ширины и высоты
		$ratio = $imageWidth / $imageHeight;
		//Присвоим переменным целевую ширину и высоту
		$targetWidth = $targetParams['width'];
		$targetHeight = $targetParams['height'];

		//Произведем расчет ширины и высоты
		if ($targetHeight) {
			if ($targetWidth / $targetHeight > $ratio) {
				$targetWidth = $targetHeight * $ratio;
			} else {
				$targetHeight = $targetWidth / $ratio;
			}
		} else {
			$targetHeight = $targetWidth / $ratio;
		}

		//Получим целевые папку для изображений и имя файла
		$targetFolder = $targetParams['newFolder'];
		$targetFilename = $targetParams['newFilename'];

		//Получим полный целевой путь вместе с именем
		$targetFilePath = sprintf('%s/%s', $targetFolder, $targetFilename);

		//Изменим разрешение файла
		$imagineFile = $this->imagine->open($originalFilePath);
		$imagineFile->resize(
			new Box($targetWidth, $targetHeight)
		)->save($targetFilePath);

		return$targetFilename;
	}
}