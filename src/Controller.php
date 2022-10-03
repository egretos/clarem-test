<?php

namespace Source;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller extends  HttpController
{
	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function index(): string
	{
		return $this->render('index.twig');
	}
	
	public function upload(Request $request): string
	{
		/** @var UploadedFile $file */
		$file = $request->files->get('file');
		
		/**
		 * We can move validation into form class
		 */
		$validator = Validation::createValidator();
		$violations = $validator->validate($file, [
			new File([
				'maxSize' => '1024k',
				'mimeTypes' => [
					'text/csv',
					'text/plain',
				]
			]),
			new NotBlank(),
		]);
		
		$content = $file->getContent();
		
		if ($violations->count()) {
			/** @var ConstraintViolation $violation */
			foreach ($violations as $violation) {
				/**
				 * We can throw exception here and handle it in core handle
				 * But we don`t have it (yet)
				 */
				return $violation->getMessage();
			}
		}
		
		/**
		 * The best way here is to use adapter to transfer data from request to DTO, then move it to service, and then transfer it to response again
		 */
		$rows = explode("\n", $content);
		$categories = [];
		
		foreach ($rows as $row) {
			$rowData = explode(",", $row);
			$categoryName = $rowData[0];
			$itemCost = $rowData[1];
			$itemQuantity = $rowData[2];
			
			$rowExpense = $itemCost * $itemQuantity;
			
			if (!($categoryName == '')) {
				$categories[$categoryName] += $rowExpense;
			}
		}
		
		return $this->render('response.twig', [
			'categories' => $categories,
		]);
	}
}