<?php
/**
 * The file that defines the base custom post type class.
 *
 * @since      1.0.0
 *
 * @package    CPT_Registrator
 * @subpackage Base
 */

namespace CPT_Registrator\Base;

/**
 * Base Taxonomy Class.
 *
 * @since      1.0.0
 * @package    CPT_Registrator
 * @subpackage Base
 * @author     Analia Mok
 */
class Taxonomy {

	/**
	 * Naming prefix for all upcoming taxonomies.
	 *
	 * @var String
	 */
	private static $prefix = '';

	/**
	 * Singular version of this CPT's name.
	 *
	 * @var String
	 */
	private static $name;

	/**
	 * WordPress compatible post type key to use instead of the name.
	 *
	 * @var string
	 */
	private static $post_key = '';

	/**
	 * Text Domain.
	 *
	 * @var String
	 */
	private static $text_domain = 'cpt_registrator';

	/**
	 * Taxonomy arguments.
	 *
	 * @var Array
	 */
	private static $args;

	/**
	 * All taxonomy labels.
	 *
	 * @var Array
	 */
	private static $labels;

	/**
	 * Singleton Instance of the current taxonomy.
	 *
	 * @var CPT
	 */
	private static $instance;

	/**
	 * Constructor
	 *
	 * Primarily for resetting static members before creating a new custom post type.
	 */
	public function __construct() {
		self::$name     = '';
		self::$post_key = '';
		self::$args     = array();
	}

	/**
	 * Static Constructor.
	 *
	 * Creates a new static instance with labels.
	 *
	 * @param String $name Post type name.
	 * @param String $description [Default=''].
	 * @return CPT  singleton instance for method chaining.
	 */
	public static function create( string $name, string $description = '' ) {
		self::$instance = new Taxonomy();
		self::$name     = $name;
		self::$instance->set_labels();
		return self::$instance;
	}

	/**
	 * Sets a prefix to use for upcoming taxonomy keys.
	 *
	 * @param string $prefix Prefix to set.
	 * @return Taxonomy   singleton instance.
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
	 * @param string $post_key Post key to register taxonomy under.
	 * @return Taxonomy The current instance.
	 */
	public function set_post_key( string $post_key ) {
		self::$post_key = $post_key;
		return $this;
	}

	/**
	 * Sets Taxonomy Labels.
	 *
	 * Sets all labels used in admin dashboard.
	 *
	 * @return void
	 */
	private function set_labels() {
        // phpcs:disable
        self::$labels = array(
            'name'                       => _x( self::$name . 's', 'Taxonomy General Name', self::$text_domain ),
            'singular_name'              => _x( self::$name, 'Taxonomy Singular Name', self::$text_domain ),
            'menu_name'                  => __( self::$name, self::$text_domain ),
            'all_items'                  => __( 'All ' . self::$name . 's', self::$text_domain ),
            'parent_item'                => __( 'Parent ' . self::$name, self::$text_domain ),
            'parent_item_colon'          => __( 'Parent ' . self::$name . ':', self::$text_domain ),
            'new_item_name'              => __( 'New ' . self::$name, self::$text_domain ),
            'add_new_item'               => __( 'Add New ' . self::$name, self::$text_domain ),
            'edit_item'                  => __( 'Edit ' . self::$name, self::$text_domain ),
            'update_item'                => __( 'Update ' . self::$name, self::$text_domain ),
            'view_item'                  => __( 'View ' . self::$name, self::$text_domain ),
            'separate_items_with_commas' => __( 'Separate items with commas', self::$text_domain ),
            'add_or_remove_items'        => __( 'Add or remove ' . strtolower( self::$name ) . 's', self::$text_domain ),
            'choose_from_most_used'      => __( 'Choose from the most used', self::$text_domain ),
            'popular_items'              => __( 'Popular ' . self::$name . 's', self::$text_domain ),
            'search_items'               => __( 'Search ' . self::$name . 's', self::$text_domain ),
            'not_found'                  => __( 'Not Found', self::$text_domain ),
            'no_terms'                   => __( 'No ' . self::$name . 's', self::$text_domain ),
            'items_list'                 => __( self::$name . 's list', self::$text_domain ),
            'items_list_navigation'      => __( self::$name . 's list navigation', self::$text_domain ),
        );
        // phpcs:enable
	}

	/**
	 * Set taxonomy arguments.
	 *
	 * @param array $custom_args Custom arguments used to override defaults.
	 * @return Taxonomy Current instance.
	 */
	public function set_args( $custom_args = array() ) {
		$new_args = array(
			'labels'            => self::$labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
		);

		self::$args = array_replace( self::$args, $new_args );

		if ( ! empty( $custom_args ) ) {
			self::$args = array_replace( self::$args, $custom_args );
		}

		return $this;
	}

	/**
	 * Override rewrite arguments.
	 *
	 * Optional method for defining the rewrite rules for this custom post type.
	 *
	 * @param array $rewrite_args Arguments to override defaults with.
	 * @return Taxonomy Current instance.
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
	 * Register taxonomy for supplied list of post types.
	 *
	 * @param array $post_types [Default=array('post)] Post types to register this taxonomy under.
	 * @return Taxonomy Current instance.
	 */
	public function register( $post_types = array( 'post' ) ) {

		$tax_qualified_name = '';

		// Set post key.
		if ( ! empty( self::$post_key ) ) {
			// Priority given to the explicitly given post key.
			$tax_qualified_name = self::$post_key;
		} else {
			// Sanitize given label name.
			$tax_qualified_name = strtolower( self::$name );
			$tax_qualified_name = str_replace( ' ', '_', $tax_qualified_name );
		}

		// Apply any prefixes.
		if ( ! empty( self::$prefix ) ) {
			$tax_qualified_name = self::$prefix . $tax_qualified_name;
		}

		// Initial Registration.
		register_taxonomy( $tax_qualified_name, $post_types, self::$args );

		// Safety Calls.
		foreach ( $post_types as $pt ) {
			register_taxonomy_for_object_type( $tax_qualified_name, $pt );
		}

		return $this;
	}
}
