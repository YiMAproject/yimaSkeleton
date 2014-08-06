<?php
/**
 * Autoloading no composer libraries,
 * defined to such below files
 */
$map      = require 'autoload_namespaces.php';
$prefixes = require 'autoload_prefixes.php';
$classMap = require 'autoload_classmap.php';

Zend\Loader\AutoloaderFactory::factory(
	array(
		'Zend\Loader\StandardAutoloader' => array(
			'autoregister_zf' => true,
			'namespaces' => $map,
			'prefixes'   => $prefixes,
		),
		'Zend\Loader\ClassMapAutoloader' => array($classMap),
	)
);