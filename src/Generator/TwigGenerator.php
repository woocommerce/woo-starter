<?php

namespace WooStarter\Generator;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use WooStarter\traits\FileHelper;
use WooStarter\traits\SlugInflector;

use Phar;

class TwigGenerator {

	use FileHelper;
	use SlugInflector;

	/**
	 * @var \Twig\Environment
	 */
	private Environment $twig;
	private string $root_directory;
	private string $slug;
	private array $data = [];

	public function __construct( string $template_directory, array $data ) {
		$loader               = new FilesystemLoader( $template_directory );
		$this->root_directory = $template_directory;
		$this->twig           = new Environment( $loader );
		$this->slug           = $data[ 'slug' ];
		$this->data           = [
			'snake_case'            => $this->snake_case( $data[ 'slug' ] ),
			'kebab_case'            => $data[ 'slug' ],
			'pascal_case'           => $this->pascal_case( $data[ 'slug' ] ),
			'upper_snake_case'      => $this->upper_snake_case( $data[ 'slug' ] ),
			'extension_name'        => $data[ 'extension_name' ],
			'author'		        => $data[ 'author' ],
			'extension_description' => $data[ 'extension_description' ],
		];
	}

	/**
	 * Render and output all templates recursively.
	 *
	 * @param string $relative_path
	 * @param string $template_directory
	 * @param string $output_directory
	 * @return string
	 */
	public function generate( string $relative_path, string $template_directory, string $output_directory ): string {
		try {
			if ( Phar::running( false ) ) {
				$templates = $this->get_phar_templates( $template_directory );
			} else {
				$templates = glob($template_directory . '/' . $relative_path . '{*,.*}', GLOB_BRACE );
			}

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

					$message = $this->generate( '', $template, $new_output_directory );

					if ( $message !== 'success' ) {
						return $message;
					}
				} else if ( is_file( $template ) ) {
					$file_name = $this->get_new_file_name( basename( $template ), $this->slug );
					$rel_path = $this->get_path_relative_to_parent( $template, $this->root_directory );
					$rendered_content = $this->twig->render( $rel_path, $this->data );

					$output_path = $output_directory . '/' . $relative_path . $file_name;
					file_put_contents( $output_path, $rendered_content );
				}
			}
			return 'success';
		} catch ( \Exception $e ) {
			// The slug is used to create the root of the extension, so it is important to remove it if the generation fails.
			$this->remove_directory( $this->slug );
			return $e->getMessage();
		}
	}
}