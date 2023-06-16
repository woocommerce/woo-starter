<?php

use lucatume\DI52\Container;
use WooStarter\App;
use Symfony\Component\Console\Application;
use WooStarter\Commands\CreateCommand;

// TODO: Setup single command application!
// TODO: Hide extra commands

try {
	require_once __DIR__ . '/../vendor/autoload.php';

	$container = new Container();
	App::setContainer( $container );

	App::setVar( 'default_slug', 'plugin-name' );

	$application = new Application();
	$application->find( 'completion' )->setHidden( true );
	$application->find( 'list' )->setHidden( true );
	$application->find( 'help' )->setHidden( true );
	$application->add( $container->make( CreateCommand::class ) );
	$application->run();
} catch ( \Exception $e ) {
	echo $e->getMessage();
	exit( 1 );
}