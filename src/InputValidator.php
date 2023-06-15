<?php

namespace WooStarter;

class InputValidator {

	public function is_kebab_case( string $string ): bool {
		return preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $string );
	}
}