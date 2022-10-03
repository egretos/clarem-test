<?php

require __DIR__ . "/vendor/autoload.php";

use Source\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('index', new Route('/', ['_controller' => Controller::class, '_method' => 'GET']));
$routes->add('upload', new Route('upload', ['_controller' => Controller::class, '_method' => 'POST']));


$request = Request::createFromGlobals();
$context = (new RequestContext())->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);
if ($parameters = $matcher->match($context->getPathInfo())) {
	$controller = $parameters['_controller'];
	$route = $parameters['_route'];
	
	$controller = new $controller;
	$result = $controller->$route($request);
}

if (isset($result)) {
	echo $result;
} else {
	echo 'not found';
}
