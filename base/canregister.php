<?php
/**
 * The file that defines the base registration trait.
 *
 * @since      1.0.0
 *
 * @package    CPT_Registrator
 * @subpackage Base
 */

namespace CPT_Registrator\Base;

/**
 * Trait defining the base methods for defining a custom data type
 * in WordPress.
 *
 * @since      0.1.0
 * @package    CPT_Registrator
 * @subpackage Base
 * @author     Analia Mok
 */
trait CanRegister {

	/**
	 * Naming prefix for subsequent instantiations of this class.
	 *
	 * @var String
	 */
	private static $prefix = '';

	/**
	 * Singular version of this class's name.
	 *
	 * @var String
	 */
	private static $name;

	/**
	 * WordPress compatible post type key to use instead of the name.
	 *
	 * @var String
	 */
	private static $post_key = '';

	/**
	 * Text Domain.
	 *
	 * @var String
	 */
	private static $text_domain = 'cpt_registrator';

	/**
	 * Type description.
	 *
	 * @var String
	 */
	private static $description = '';

	/**
	 * Required arguments.
	 *
	 * @var Array
	 */
	private static $args;

	/**
	 * All labels.
	 *
	 * @var Array
	 */
	private static $labels;

	/**
	 * Singleton Instance.
	 *
	 * @var Object  The current registration class.
	 */
	private static $instance;

	/**
	 * Sets a prefix to use for upcoming registrations.
	 *
	 * @param string $prefix Prefix to set.
	 * @return Object   singleton instance.
	 */
	public static function set_prefix( string $prefix ) {
		// Sanitize prefix.
		$sanitized_prefix = str_replace( ' ', '_', $prefix );

		self::$prefix = $sanitized_prefix;
		return self::$instance;
	}

	/**
	 * Set a post key different from the name.
	 *
	 * @param string $post_key Post key to use during registration.
	 * @return Object The current instance.
	 */
	public function set_post_key( string $post_key ) {
		self::$post_key = $post_key;
		return $this;
	}

	/**
	 * Helper method for getting around translating strings containing only a placeholder.
	 *
	 * @param String $context Translation context.
	 * @return String translated singular name label.
	 */
	public static function get_singular_name_i18n( $context ) {
		/* translators: %s Term name followed by text domain. */
		$substituted_singular_label = sprintf( _x( '<span>%s</span>', '%s', '%s' ), self::$name, $context, self::$text_domain );
		$substituted_singular_label = str_replace( '<span>', '', $substituted_singular_label );
		$substituted_singular_label = str_replace( '</span>', '', $substituted_singular_label );
		return $substituted_singular_label;
	}

	/**
	 * Override rewrite arguments.
	 *
	 * Optional method for defining the rewrite rules for this custom post type.
	 *
	 * @param Array $rewrite_args Array arguments to override default rewrite rules.
	 * @return Object   Current singleton instance.
	 */
	public function set_rewrite( $rewrite_args = array() ) {
		// Slugify name.
		$slug = str_replace( ' ', '-', strtolower( self::$name ) );

		// Using WordPress Defaults.
		$rewrite = array(
			'slug'         => $slug,
			'with_front'   => true,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE,
		);

		self::$args['rewrite'] = array_replace( $rewrite, $rewrite_args );

		return $this;
	}

	/**
	 * Configures this class for the REST API.
	 *
	 * @param string $rest_base Base url used in REST API.
	 * @param string $rest_controller_class [Default=WP_REST_Posts_Controller if CPT, WP_REST_Terms_Controller if Taxonomy]
	 *                                      Name of controller to handle RESTful requests.
	 * @return CPT singleton instance.
	 */
	public function make_restful( $rest_base = '', $rest_controller_class = '' ) {
		$rest_args = array(
			'show_in_rest' => true,
		);

		if ( ! empty( $rest_base ) ) {
			$rest_args['rest_base'] = $rest_base;
		}

		if ( ! empty( $rest_controller_class ) ) {
			$rest_args['rest_controller_class'] = $rest_controller_class;
		}

		self::$args = array_merge( self::$args, $rest_args );
		return $this;
	}
}
