<?php

namespace Source;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HttpController
{
	private static ?Environment $twig = null;
	
	public function __construct() {
		if (!self::$twig) {
			$loader = new FilesystemLoader(__DIR__ . "/../twig");
			self::$twig = new Environment($loader, [
				'cache' => __DIR__ . "/../cache/twig",
			]);
		}
	}
	
	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	protected function render(string $template, array $options = []): string
	{
		return self::$twig->render($template, $options);
	}
}