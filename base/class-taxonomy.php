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
 * This is the base class for creating and registering a new taxonomy.
 *
 * @since      0.1.0
 * @package    CPT_Registrator
 * @subpackage Base
 * @author     Analia Mok
 */
class Taxonomy {

	use CanRegister;

	/**
	 * Constructor
	 *
	 * Primarily for resetting static members before creating a new taxonomy.
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
	 * @return Taxonomy  singleton instance for method chaining.
	 */
	public static function create( string $name, string $description = '' ) {
		self::$instance = new Taxonomy();
		self::$name     = $name;
		self::$instance->set_labels();
		return self::$instance;
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
