<?php
/**
 * The file that defines the core plugin class
 *
 * @since      1.0.0
 * @package    CPT_Registrator
 */

namespace CPT_Registrator;

use \CPT_Registrator\Base\CPT;

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    CPT_Registrator
 * @author     Analia Mok
 */
class CPTRegistrator {

	/**
	 * Method to run on plugin activation.
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function activate() {
		// Nothing...
	}

	/**
	 * Method to run on plugin deactivation.
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function deactivate() {
		// Nothing...
	}

	/**
	 * Autoload function for registering all classes.
	 *
	 * @since 0.2.0
	 * @return void
	 */
	public static function autoload() {
		$base_classes_dir   = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR;
		$helper_classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR;
		// TODO.
	}
}
