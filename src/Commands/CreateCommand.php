<?php

namespace WooStarter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use WooStarter\App;
use WooStarter\Generator\TwigGenerator;

class CreateCommand extends Command {

	public static $defaultName = 'create';
	protected static $defaultDescription = 'Create a new WooExtension project.';
	private $banner = <<<BANNER
 __          __          _ 
 \ \        / /         | |
  \ \  /\  / /__   ___  | |
   \ \/  \/ / _ \ / _ \ | |
    \  /\  / (_) | (_)  |_|
     \/  \/ \___/ \___/ (_)

BANNER;

	public function __construct() {
		parent::__construct();
	}

	protected function configure() {
		$this
			->setHelp( 'The create command guides the user through the process of creating a standard WooCommerce Extension.' );
//			->setDefinition( [
//				new InputArgument( 'extension_name', InputArgument::OPTIONAL, 'The name of the extension to create.' ),
//				new InputArgument( 'extension_slug', InputArgument::OPTIONAL, 'The slug of the extension to create.' ),
//			] );
	}

	public function execute( InputInterface $input, OutputInterface $output ): int {
		$helper = $this->getHelper('question' );

		$output->writeln([
			'<info>Welcome to the WooCommerce Extension Starter!</info>',
			sprintf( '<fg=#674399>%s</>', $this->banner ),
			'<comment>By answering a few questions, we will be able to generate a new WooCommerce Extension for you.</comment>',
			'',
		]);

		// $question       = new Question( '<question>What is the name of your extension?</question> ' );
		// $extension_name = $helper->ask( $input, $output, $question );

		// $output->writeln( sprintf( '<info>Extension Name:</info> %s', $extension_name ) );

		// $question       = new Question( '<question>What is the slug of your extension?</question> ' );
		// $extension_slug = $helper->ask( $input, $output, $question );

		// $question = new ConfirmationQuestion( '<question>Do you want dependency injection in your project?</question>', false );
		// $wants_di = $helper->ask( $input, $output, $question );

		// $output->writeln( sprintf( '<info>Extension Name:</info> %s', $extension_name ) );
		// $output->writeln( sprintf( '<info>Extension Name:</info> %s', $extension_slug ) );

		// if ( $wants_di ) {
		// 	$output->writeln( '<info>Dependency Injection:</info> Yes' );
		// }

		$template_directory = __DIR__ . '/../templates/default/plugin-name';
		$generator = new TwigGenerator( $template_directory );
		$generator->generate( '', $template_directory, './plugin-name' );

		return Command::SUCCESS;
	}
}