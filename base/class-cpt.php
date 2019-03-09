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
 * Base Custom Post Type class.
 *
 * This is a base class for creating and registering a new custom post type.
 *
 * @since      0.1.0
 * @package    CPT_Registrator
 * @subpackage Base
 * @author     Analia Mok
 */
class CPT {

	use CanRegister;

	/**
	 * Constructor
	 *
	 * Primarily for resetting static members before creating a new custom post type.
	 */
	public function __construct() {
		self::$name        = '';
		self::$description = '';
		self::$post_key    = '';
		self::$args        = array();
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
		self::$instance    = new CPT();
		self::$name        = $name;
		self::$description = $description;
		self::$instance->set_labels();
		return self::$instance;
	}

	/**
	 * Sets CPT Labels.
	 *
	 * Sets all labels used in admin dashboard.
	 *
	 * @return void
	 */
	private function set_labels() {
		// phpcs:disable
		// FIXME: Want to follow WordPress's I18N standards.
		self::$labels = array(
			'name'                  => _x( self::$name, 'Post Type General Name', self::$text_domain ),
			'singular_name'         => _x( self::$name, 'Post Type Singular Name', self::$text_domain ),
			'menu_name'             => __( self::$name . 's', self::$text_domain ),
			'name_admin_bar'        => __( self::$name, self::$text_domain ),
			'archives'              => __( self::$name . ' Archives', self::$text_domain ),
			'attributes'            => __( self::$name . ' Attributes', self::$text_domain ),
			'all_items'             => __( 'All ' . self::$name . 's', self::$text_domain ),
			'add_new_item'          => __( 'Add New ' . self::$name, self::$text_domain ),
			'add_new'               => __( 'Add New ' . self::$name, self::$text_domain ),
			'new_item'              => __( 'New ' . self::$name, self::$text_domain ),
			'edit_item'             => __( 'Edit ' . self::$name, self::$text_domain ),
			'update_item'           => __( 'Update ' . self::$name, self::$text_domain ),
			'view_item'             => __( 'View ' . self::$name, self::$text_domain ),
			'view_items'            => __( 'View ' . self::$name . 's', self::$text_domain ),
			'search_items'          => __( 'Search ' . self::$name . 's', self::$text_domain ),
			'not_found'             => __( 'Not found', self::$text_domain ),
			'not_found_in_trash'    => __( 'Not found in Trash', self::$text_domain ),
			'featured_image'        => __( 'Featured Image', self::$text_domain ),
			'set_featured_image'    => __( 'Set featured image', self::$text_domain ),
			'remove_featured_image' => __( 'Remove featured image', self::$text_domain ),
			'use_featured_image'    => __( 'Use as featured image', self::$text_domain ),
			'insert_into_item'      => __( 'Insert into ' . self::$name, self::$text_domain ),
			'uploaded_to_this_item' => __( 'Uploaded to this ' . self::$name, self::$text_domain ),
			'items_list'            => __( 'Items list', self::$text_domain ),
			'items_list_navigation' => __( 'Items list navigation', self::$text_domain ),
			'filter_items_list'     => __( 'Filter ' . self::$name . ' list', self::$text_domain ),
		);
		// phpcs:enable
	}

	/**
	 * Set all required arguments for the post type.
	 *
	 * Sets base arguments for defining a custom post type.
	 *
	 * @param string $dashicon [Default="dashicons-admin-post"].
	 * @param array  $custom_args [Default="array"] for overriding arguments defined in default.
	 * @return CPT singleton instance.
	 */
	public function set_args( $dashicon = 'dashicons-admin-post', $custom_args = array() ) {
		// phpcs:disable
		$new_args = array(
			'label'               => __( self::$name, self::$text_domain ),
			'labels'              => self::$labels,
			'hierarchical'        => false, // FUTURE TODO: Dynamically set.
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => $dashicon,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'supports'            => array( 'title', 'editor' ),
		);
		// phpcs:enable
		self::$args = array_replace( self::$args, $new_args );

		if ( ! empty( $custom_args ) ) {
			self::$args = array_replace( self::$args, $custom_args );
		}

		// Set Customizable values.
		if ( ! empty( self::$description ) ) {
			// phpcs:disable
			self::$args['description'] = __( self::$description, self::$text_domain );
			// phpcs:enable
		}

		return $this;
	}

	public function make_restful( $rest_base='', $rest_controller_class = '' ) {
		// TODO
	}

	/**
	 * Register post type with WordPress.
	 *
	 * Final method that should be called in a method chain. Will register the custom post type
	 * with WordPress.
	 *
	 * @return CPT   singleton instance.
	 */
	public function register() {
		$cpt_qualified_name = '';

		if( ! empty( self::$post_key ) ) {
			$cpt_qualified_name = self::$post_key;
		} else {
			// Sanitize given label name.
			$cpt_qualified_name = strtolower( self::$name );
			$cpt_qualified_name = str_replace( ' ', '_', $cpt_qualified_name );

			// Apply any prefixes.
			if ( ! empty( self::$prefix ) ) {
				$cpt_qualified_name = self::$prefix . $cpt_qualified_name;
			}
		}

		// Register Custom Post Type.
		register_post_type( $cpt_qualified_name, self::$args );
		return $this;
	}

}
