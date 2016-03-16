<?php

namespace Montesjmm\ResizeAndWatermark;

use Illuminate\Support\Facades\File as IFile;

class File
{

	const SYMFONY_UPLOADED_FILE_NAMESPACE = 'Illuminate\Http\UploadedFile';

	protected $file;

	protected $paths;

	protected $filenameWithoutExtension;

	protected $privateFullFilename;

	public function __construct($file, $slug)
	{
		$this->paths                    = $this->checkPaths();
		$this->filenameWithoutExtension = $this->makeFilenameWithoutExtension($slug);
		$this->privateFullFilename      = $this->paths['private'] . $this->filenameWithoutExtension . '_orig.jpg';

		$this->moveFile($file);
	}

	protected function checkPaths()
	{
		$year              = date('Y');
		$month             = date('m');
		$publicUploadPath  = base_path() . '/public/uploads/';
		$privateUploadPath = base_path() . '/private-uploads/';

		if (! IFile::exists($publicUploadPath  . $year . '/' . $month)) {
			IFile::makeDirectory($publicUploadPath  . $year . '/' . $month, 0755, true);
		}

		if (! IFile::exists($privateUploadPath . $year . '/' . $month)) {
			IFile::makeDirectory($privateUploadPath . $year . '/' . $month, 0755, true);
		}

		return [
			'public'  => $publicUploadPath . $year . '/' . $month . '/',
			'private' => $privateUploadPath . $year . '/' . $month . '/',
		];
	}

	protected function makeFilenameWithoutExtension($slug)
	{
		$filenameWithoutExtension = date('Y') . date('m') . date('d') . '-' . $slug;

		$cont = '';
		while (file_exists($this->paths['private'] . $filenameWithoutExtension . $cont . '_orig.jpg')) {
			$cont === '' ? $cont = 1 : $cont++;
		}

		return $filenameWithoutExtension . $cont;
	}

	protected function moveFile($file)
	{
		if (is_string($file) && filter_var($file, FILTER_VALIDATE_URL) !== false) {
			file_put_contents($this->privateFullFilename, file_get_contents($file));

		} elseif (is_string($file) && file_exists($file)) {
			rename($file, $this->privateFullFilename);

		} elseif ($file = $this->isSymfonyUploadedFile($file)) {
			$file->move($this->paths['private'], $this->filenameWithoutExtension . '_orig.jpg');

		} else {
			throw new Exception('Wrong type of input file');
		}
	}

	protected function isSymfonyUploadedFile($file)
	{
		if (get_class($file) == self::SYMFONY_UPLOADED_FILE_NAMESPACE) {
			return $file;
		}

		if (get_class(array_values($file)[0]) == self::SYMFONY_UPLOADED_FILE_NAMESPACE) {
			return array_values($file)[0];
		}

		return false;
	}

	public function getPaths()
	{
		return $this->paths;
	}

	public function getFilenameWithoutExtension()
	{
		return $this->filenameWithoutExtension;
	}

	public function getPrivateFullFilename()
	{
		return $this->privateFullFilename;
	}
}
