<?php
/**
 * Module Base
 * 
 * Base class for all modules in OKThemes Toolkit
 * following Neuron Builder's pattern
 * 
 * @since 1.0.0
 */

namespace OKThemes\Toolkit\Elementor\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Module_Base {
	/**
	 * @var Module_Base[]
	 */
	private static $instances = [];

	/**
	 * Module constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	final public static function instance() {
		if ( empty( static::$instances[ static::class_name() ] ) ) {
			static::$instances[ static::class_name() ] = new static();
		}

		return static::$instances[ static::class_name() ];
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public static function class_name() {
		return get_called_class();
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public static function is_active() {
		return true;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return '';
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 */
	protected function init() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function elementor_init() {
		// Override in child classes
	}
}