<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             0.1.0
 * @package           CPT_Registrator
 *
 * @wordpress-plugin
 * Plugin Name:       CPT Registrator
 * Description:       Plugin framework for auto-registering and defining custom post types.
 * Version:           0.1.0
 * Author:            Analia Mok
 * Author URI:        https://analiamok.netlify.com
 * Requires PHP:      7.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cpt-registrator
 * Domain Path:       /languages
 */

namespace CPT_Registrator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'CPT_REGISTRATOR_VERSION', '1.0.0' );

/**
 * Execute plugin activation actions.
 *
 * @return void
 */
function activate_cpt_registrator() {
	require_once plugin_dir_path( __FILE__ ) . 'class-cptregistrator.php';
	CPTRegistrator::activate();
}

/**
 * Execute plugin deactivation actions.
 *
 * @return void
 */
function deactivate_cpt_registrator() {
	require_once plugin_dir_path( __FILE__ ) . 'class-cptregistrator.php';
	CPTRegistrator::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_cpt_registrator' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_cpt_registrator' );

/**
 * The core plugin classes.
 */

require_once plugin_dir_path( __FILE__ ) . 'helpers/class-dashicons.php';
require_once plugin_dir_path( __FILE__ ) . 'base/class-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'base/class-taxonomy.php';
require_once plugin_dir_path( __FILE__ ) . 'class-cptregistrator.php';
