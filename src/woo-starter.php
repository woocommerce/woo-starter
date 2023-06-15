<?php

use lucatume\DI52\Container;
use WooStarter\App;
use Symfony\Component\Console\Application;
use WooStarter\Commands\CreateCommand;

// TODO: Setup single command application!

try {
	require_once __DIR__ . '/../vendor/autoload.php';

	$container = new Container();
	App::setContainer( $container );

	$application = new Application();
	$application->add( $container->make( CreateCommand::class ) );
	$application->run();
} catch ( \Exception $e ) {
	echo $e->getMessage();
	exit( 1 );
}