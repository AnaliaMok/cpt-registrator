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
	 * Requires all necessary classes.
	 *
	 * Temporary method to consolidate requires before an autoloader is implemented.
	 *
	 * @return void
	 */
	public static function load() {
		// Register autoloader.
		\spl_autoload_register( array( self::class, 'autoload' ) );
	}

	/**
	 * Autoload function for registering all classes.
	 *
	 * @since 0.2.0
	 * @param String $class_name Fully qualified name of the unrequired class.
	 * @return void|boolean Nothing on success; false if class doesn't belong to this package.
	 */
	public static function autoload( $class_name ) {
		
		// Make sure to not interfere with other autoloaders.
		if ( false === strpos( $class_name, 'CPT_Registrator' ) ) {
			return false;
		}

		$last_slash       = strrpos( $class_name, '\\' );
		$short_class_name = substr( $class_name, $last_slash + 1 );

		if ( false !== strpos( $class_name, 'Base' ) ) {
			$base_classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR;

			if ( false !== strpos( $class_name, 'CanRegister' ) ) {
				// Requiring trait.
				require_once $base_classes_dir . strtolower( $short_class_name ) . '.php';
			} else {
				// Requiring class.
				require_once $base_classes_dir . 'class-' . strtolower( $short_class_name ) . '.php';
			}
		} else {
			// Helper Classes.
			$helper_classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR;
			require_once $helper_classes_dir . 'class-' . strtolower( $short_class_name ) . '.php';
		}
	}
}
