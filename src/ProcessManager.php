<?php

namespace WooStarter;

use Symfony\Component\Console\Output\OutputInterface;

class ProcessManager {
	private $commands;

	public function __construct( array $commands ) {
		$this->commands = $commands;
	}

	public function set_commands( array $commands ) {
		$this->commands = $commands;
	}

	public function execute( OutputInterface $output ): bool {
		$success         = true;

		foreach ( $this->commands as $index => $command ) {
			exec( $command, $response, $code );

			if ( $code !== 0 ) {
				$output->writeln( sprintf( '<error>%s</error>', implode( "\n", $response ) ) );
				return false;
			}
		}

		return $success;
	}
}