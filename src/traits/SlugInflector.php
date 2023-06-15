<?php

namespace WooStarter\traits;

trait SlugInflector {
	/**
	 * Convert a string to snake case.
	 *
	 * @param string $string
	 * @return string
	 */
	function snake_case( string $string ): string {
		return str_replace('-', '_', $string );
	}

	/**
	 * Convert a string to pascal case.
	 *
	 * @param string $string
	 * @return string
	 */
	function pascal_case( string $string ): string {
		$str = str_replace( '-', ' ', $string );
		$str = ucwords( $str );
		return str_replace(' ', '_', $str );
	}

	/**
	 * Convert a string to upper case.
	 *
	 * @param string $string
	 * @return string
	 */
	function upper_case( string $string ): string {
		return strtoupper( $string );
	}

	/**
	 * Convert a string to upper snake case.
	 *
	 * @param string $string
	 * @return string
	 */
	function upper_snake_case( string $string ): string {
		return $this->upper_case( $this->snake_case( $string ) );
	}
}