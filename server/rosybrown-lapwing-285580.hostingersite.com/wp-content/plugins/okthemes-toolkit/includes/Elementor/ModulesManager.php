<?php
/**
 * Modules Manager
 * 
 * Handles all the modules
 * via the namespacing.
 * Following Neuron Builder's pattern
 * 
 * @since 1.0.0
 */

namespace OKThemes\Toolkit\Elementor;

use OKThemes\Toolkit\Elementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ModulesManager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function __construct() {
		$modules = [
			'motion-fx',
			'sticky',
			// Add more modules here as needed
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\OKThemes\Toolkit\Elementor\Modules\\' . $class_name . '\Module';

			if ( class_exists( $class_name ) && $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	public function get_modules( $module_name = null ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}