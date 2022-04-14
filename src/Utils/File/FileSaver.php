<?php

namespace App\Utils\File;

use App\Utils\Filesystem\FilesystemWorker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSaver
{

	/**
	 * @var \Symfony\Component\String\Slugger\SluggerInterface
	 */
	private SluggerInterface $slugger;

	private string $uploadsTempDir;

	/**
	 * @var \App\Utils\Filesystem\FilesystemWorker
	 */
	private FilesystemWorker $filesystemWorker;

	public function __construct(SluggerInterface $slugger, FilesystemWorker $filesystemWorker,string $uploadsTempDir)
	{
		$this->slugger = $slugger;
		$this->uploadsTempDir = $uploadsTempDir;
		$this->filesystemWorker = $filesystemWorker;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
	 *
	 * @return string|null
	 */
	public function saveUploadedFileIntoTemp(uploadedFile $uploadedFile): ?string
	{
		//Получим оригинальное название загружмаемого файла
		$originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
		//Изменим название, чтобы оно могло содержать нестандартные символы
		$safeFileName = $this->slugger->slug($originalFileName);

		//Получим полноценное название файла c уникальным индефикатором
		$filename = sprintf('%s-%s.%s', $safeFileName, uniqid(), $uploadedFile->guessExtension());

		//Создададим папку, если её не существует
		$this->filesystemWorker->createFolderIfItNotExist($this->uploadsTempDir);

		//Переместим файл во временную папку
		try {
			$uploadedFile->move($this->uploadsTempDir, $filename);
		} catch (FileException $exception) {
			return null;
		}

		return $filename;
	}
}