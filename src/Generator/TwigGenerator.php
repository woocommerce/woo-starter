<?php

namespace WooStarter\Generator;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigGenerator {

	use \WooStarter\traits\FileHelper;

	/**
	 * @var \Twig\Environment
	 */
	private Environment $twig;
	private string $root_directory;

	public function __construct( string $template_directory ) {
		$loader               = new FilesystemLoader( $template_directory );
		$this->root_directory = $template_directory;
		$this->twig           = new Environment( $loader );
	}

	/**
	 * Render and output all templates recursively.
	 *
	 * @param string $relative_path
	 * @param string $template_directory
	 * @param string $output_directory
	 */
	public function generate( string $relative_path, string $template_directory, string $output_directory ) {
		$templates = glob($template_directory . '/' . $relative_path . '{*,.*}', GLOB_BRACE );

		if ( ! is_dir( $output_directory ) ) {
			mkdir( $output_directory, 0755, true );
		}

		foreach ( $templates as $template ) {
			if ( $this->is_valid_directory( $template, $template_directory ) ) {
				$new_relative_path = $this->get_path_relative_to_parent( $template, $this->root_directory );
				$new_output_directory = $output_directory . '/' . $new_relative_path;

				if ( ! is_dir( $new_output_directory ) ) {
					mkdir( $new_output_directory, 0755, true );
				}

				$this->generate( '', $template, $new_output_directory );
			} else if ( is_file( $template ) ) {
				$file_name = $this->get_original_file_name( basename( $template ) );
				echo 'Writing to ' . $file_name . "\n";
				$rel_path = $this->get_path_relative_to_parent( $template, $this->root_directory );
				$rendered_content = $this->twig->render( $rel_path, [] );

				$output_path = $output_directory . '/' . $relative_path . $file_name;
				file_put_contents( $output_path, $rendered_content );
			}
		}
	}
}