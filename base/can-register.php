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
	 * @var Object 	The current registration class.
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
	 * @param string $post_key
	 * @return Object The current instance.
	 */
	public function set_post_key( string $post_key ) {
		self::$post_key = $post_key;
		return $this;
	}

	/**
	 * Override rewrite arguments.
	 *
	 * Optional method for defining the rewrite rules for this custom post type.
	 *
	 * @param String $slug Proper slug to use for this post type.
	 * @return Object   Current singleton instance.
	 */
	public function set_rewrite( $rewrite_args = array() ) {
		// Slugify name.
		$slug = str_replace( ' ', '-', strtolower( self::$name ) );

		// Using Wordpress Defaults.
        $rewrite = array(
			'slug'			=> $slug,
			'with_front'	=> true,
			'hierarchical'  => false,
			'ep_mask'		=> EP_NONE,
		);

		self::$args['rewrite'] = array_replace( $rewrite, $rewrite_args );

		return $this;
	}
}