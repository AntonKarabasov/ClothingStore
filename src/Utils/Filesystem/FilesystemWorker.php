<?php

namespace App\Utils\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class FilesystemWorker
{

	/**
	 * @var \Symfony\Component\Filesystem\Filesystem
	 */
	private Filesystem $filesystem;

	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}

	/**
	 * @param string $folder
	 *
	 * @return void
	 */
	public function createFolderIfItNotExist(string $folder): void
	{
		if (!$this->filesystem->exists($folder)) {
			$this->filesystem->mkdir($folder);
		}
	}
}