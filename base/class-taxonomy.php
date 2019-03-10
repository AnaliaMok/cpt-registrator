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

		$substituted_singular_label = $this->get_singular_name_i18n( 'Taxonomy Singular Name' );

		self::$labels = array(
			/* translators: %s Term name followed by text domain. */
			'name'                       => sprintf( _x( '%ss', 'Taxonomy General Name', '%s' ), self::$name, self::$text_domain ),
			'singular_name'              => $substituted_singular_label,
			'menu_name'                  => $substituted_singular_label,
			/* translators: %s Term name followed by text domain. */
			'all_items'                  => sprintf( __( 'All %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'parent_item'                => sprintf( __( 'Parent %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'parent_item_colon'          => sprintf( __( 'Parent %s:', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'new_item_name'              => sprintf( __( 'New %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'add_new_item'               => sprintf( __( 'Add New %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'edit_item'                  => sprintf( __( 'Edit %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'update_item'                => sprintf( __( 'Update %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'view_item'                  => sprintf( __( 'View %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'separate_items_with_commas' => sprintf( __( 'Separate items with commas', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'add_or_remove_items'        => sprintf( __( 'Add or remove %ss', '%s' ), strtolower( self::$name ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'popular_items'              => sprintf( __( 'Popular %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'search_items'               => sprintf( __( 'Search %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'not_found'                  => sprintf( __( 'Not Found', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'no_terms'                   => sprintf( __( 'No %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'items_list'                 => sprintf( __( '%ss list', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'items_list_navigation'      => sprintf( __( '%ss list navigation', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
		);
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
