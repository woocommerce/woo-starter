<?php

namespace WooStarter\traits;

use WooStarter\App;

trait FileHelper {
	/**
	 * Get the original file name without the .twig extension from a Twig template file.
	 *
	 * @param string $twig_template
	 * @return string|null
	 */
	function get_original_file_name( string $twig_template ): ?string  {
		$pattern = '/(.+)\.twig$/';
		preg_match( $pattern, $twig_template, $matches );

		return $matches[1] ?? null;
	}

	/**
	 * Get the output path for a template.
	 *
	 * @param string $template
	 * @param string $template_directory
	 * @param string $output_directory
	 * @return string
	 */
	function get_output_path( string $template, string $template_directory, string $output_directory ): string {
		$relative_path = str_replace( $template_directory, '', $template );
		$output_path = rtrim( $output_directory, '/') . $relative_path;

		return $output_path;
	}

	/**
	 * Get the path relative to the parent directory.
	 *
	 * @param string $path
	 * @param string $parent
	 * @return string
	 */
	function get_path_relative_to_parent( string $path, string $parent ): string {
		$relative_path = str_replace( $parent, '', $path );
		$relative_path = ltrim( $relative_path, '/' );

		if ( in_array( $relative_path, [ '.', '..' ] ) ) {
			return '';
		}

		return $relative_path;
	}

	/**
	 * Check if a path is a valid directory.
	 *
	 * @param string $path
	 * @param string $parent
	 * @return bool
	 */
	function is_valid_directory ( string $path, $parent ): bool {
		return is_dir( $path ) && ! empty( $this->get_path_relative_to_parent( $path, $parent ) );
	}

	/**
	 * Get the new file name for a template.
	 *
	 * @param string $file_name
	 * @param string $new_slug
	 * @return string
	 */
	function get_new_file_name( string $file_name, string $new_slug ): string {
		$original_file_name = $this->get_original_file_name( $file_name );
		return str_replace( App::getVar( 'default_slug' ), $new_slug, $original_file_name );
	}
}