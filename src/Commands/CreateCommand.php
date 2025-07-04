<?php

namespace WooStarter\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use WooStarter\App;
use WooStarter\Generator\TwigGenerator;
use WooStarter\InputValidator;
use WooStarter\ProcessManager;

use Phar;

// TODO: Add DI plugin templates.
class CreateCommand extends Command {

	public static $defaultName = 'create';
	protected static $defaultDescription = 'Create a new WooExtension project.';
	protected InputValidator $validator;

	private $banner = <<<BANNER
 __          __          _ 
 \ \        / /         | |
  \ \  /\  / /__   ___  | |
   \ \/  \/ / _ \ / _ \ | |
    \  /\  / (_) | (_)  |_|
     \/  \/ \___/ \___/ (_)

BANNER;

	/**
	 * @throws \lucatume\DI52\ContainerException
	 */
	public function __construct() {
		parent::__construct();

		$this->validator = App::make( InputValidator::class );
	}

	protected function configure() {
		$this
			->setHelp( 'The create command guides the user through the process of creating a standard WooCommerce Extension.' );
	}

	public function execute( InputInterface $input, OutputInterface $output ): int {
		$helper = $this->getHelper('question' );

		$output->writeln([
			'<info>Welcome to the WooCommerce Extension Starter!</info>',
			sprintf( '<fg=#674399>%s</>', $this->banner ),
			'<comment>By answering a few questions, we will be able to generate a new WooCommerce Extension for you.</comment>',
			'',
		]);

		$question       = new Question( '<question>What is the name of your extension?</question> ' );
		$extension_name = $helper->ask( $input, $output, $question );

		$output->writeln( sprintf( '<info>Extension Name:</info> %s', $extension_name ) );

		$question = new Question( '<question>What is the slug of your extension?</question> ' );
		$question->setValidator( function ( $answer ) {
			if ( ! $this->validator->is_kebab_case( $answer ) ) {
				throw new \RuntimeException(
					'The extension slug must be in kebab-case.'
				);
			}

			return $answer;
		} );
		$question->setMaxAttempts(2 );
		$extension_slug = $helper->ask( $input, $output, $question );


		$question = new Question( '<question>Who is the author of this extension?</question> ' );
		$question->setValidator( function ( $answer ) {
			if ( is_null( $answer ) ) {
				throw new \RuntimeException(
					'The author name cannot be empty.'
				);
			}

			return $answer;
		} );
		$question->setMaxAttempts(2 );
		$author   = $helper->ask( $input, $output, $question );

		$question    = new Question( '<question>Please provide a description for your extension.</question> ' );
		$question->setValidator( function ( $answer ) {
			if ( is_null( $answer ) ) {
				throw new \RuntimeException(
					'The description cannot be empty.'
				);
			}

			return $answer;
		} );
		$question->setMaxAttempts(2 );
		$description = $helper->ask( $input, $output, $question );

//		$question = new ConfirmationQuestion( '<question>Do you want dependency injection in your project?</question>', false );
//		$wants_di = $helper->ask( $input, $output, $question );

//		if ( $wants_di ) {
//			$output->writeln( '<info>Dependency Injection:</info> Yes' );
//		}

		$data = [
			'slug'                  => $extension_slug,
			'extension_name'        => $extension_name,
			'author'                => $author,
			'extension_description' => $description
		];

		$progress_bar = new ProgressBar( $output );

		/**
		 * If we are running from a phar file, we need to use the phar file as the template directory.
		 */
		if ( Phar::running( false ) ) {
			$directory_path     = 'src/templates/default/' . App::getVar( 'default_slug' );
			$phar_file           = Phar::running( false );
			$template_directory = 'phar://' . $phar_file . '/' . $directory_path;
		} else {
			$template_directory = __DIR__ . '/../templates/default/' . App::getVar( 'default_slug' );
		}

		$output->writeln( '<info>Generating Extension Files...</info>' );
		$progress_bar = new ProgressBar( $output );
		$progress_bar->start();

		$generator = new TwigGenerator( $template_directory, $data, $output );
		$message = $generator->generate( '', $template_directory, $extension_slug );

		$progress_bar->finish();

		if ( $message !== 'success'  ) {
			$output->writeln( sprintf( '<error>%s</error>', $message ) );
			return Command::FAILURE;
		}

		$output->writeln( '<info>Generating Extension Files...</info>' );
		$progress_bar = new ProgressBar( $output );
		$progress_bar->start();

		$commands = [
			"cd $extension_slug && composer install",
			"cd $extension_slug && npm install"
		];

		$process_manager = new ProcessManager( $commands );
		$success         = $process_manager->execute( $output );

		$progress_bar->finish();

		// Check if the commands executed successfully
		if ( $success ) {
			$output->writeln( '<info>Dependencies Successfully Installed!.</info>' );
		} else {
			return Command::FAILURE;
		}

		return Command::SUCCESS;
	}
}