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

		$substituted_name_label     = $this->get_singular_name_i18n( 'Post Type General Name' );
		$substituted_singular_label = $this->get_singular_name_i18n( 'Post Type Singular Name' );

		self::$labels = array(
			'name'                  => $substituted_name_label,
			'singular_name'         => $substituted_singular_label,
			/* translators: %s Term name followed by text domain. */
			'menu_name'             => sprintf( __( '%ss', '%s' ), self::$name, self::$text_domain ),
			'name_admin_bar'        => $substituted_name_label,
			/* translators: %s Term name followed by text domain. */
			'archives'              => sprintf( __( '%s Archives', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'attributes'            => sprintf( __( '%s Attributes', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'all_items'             => sprintf( __( 'All %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'add_new_item'          => sprintf( __( 'Add New %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'add_new'               => sprintf( __( 'Add New %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'new_item'              => sprintf( __( 'New %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'edit_item'             => sprintf( __( 'Edit %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'update_item'           => sprintf( __( 'Update %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'view_item'             => sprintf( __( 'View %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'view_items'            => sprintf( __( 'View %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'search_items'          => sprintf( __( 'Search %ss', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'not_found'             => sprintf( __( 'Not found', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'not_found_in_trash'    => sprintf( __( 'Not found in Trash', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'featured_image'        => sprintf( __( 'Featured Image', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'set_featured_image'    => sprintf( __( 'Set featured image', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'remove_featured_image' => sprintf( __( 'Remove featured image', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'use_featured_image'    => sprintf( __( 'Use as featured image', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'insert_into_item'      => sprintf( __( 'Insert into %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', '%s' ), self::$name, self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'items_list'            => sprintf( __( 'Items list', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'items_list_navigation' => sprintf( __( 'Items list navigation', '%s' ), self::$text_domain ),
			/* translators: %s Term name followed by text domain. */
			'filter_items_list'     => sprintf( __( 'Filter %s list', '%s' ), self::$name, self::$text_domain ),
		);
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

		$substituted_singular_label = $this->get_singular_name_i18n( 'Post Type Singular Name' );

		$new_args = array(
			'label'               => $substituted_singular_label,
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

		self::$args = array_replace( self::$args, $new_args );

		if ( ! empty( $custom_args ) ) {
			self::$args = array_replace( self::$args, $custom_args );
		}

		// Set Customizable values.
		if ( ! empty( self::$description ) ) {
			/* translators: %s Term name followed by text domain. */
			$description               = sprintf( __( '<span>%s</span>', '%s' ), self::$description, self::$text_domain );
			$description               = str_replace( '<span>', '', $description );
			$description               = str_replace( '</span>', '', $description );
			self::$args['description'] = $description;
		}

		return $this;
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

		if ( ! empty( self::$post_key ) ) {
			$cpt_qualified_name = self::$post_key;
		} else {
			// Sanitize given label name.
			$cpt_qualified_name = strtolower( self::$name );
			$cpt_qualified_name = str_replace( ' ', '_', $cpt_qualified_name );
		}

		// Apply any prefixes.
		if ( ! empty( self::$prefix ) ) {
			$cpt_qualified_name = self::$prefix . $cpt_qualified_name;
		}

		// Register Custom Post Type.
		register_post_type( $cpt_qualified_name, self::$args );
		return $this;
	}

}
