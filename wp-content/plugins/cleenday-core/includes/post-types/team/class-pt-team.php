<?php
defined('ABSPATH') || exit; // Abort, if called directly.

/**
 * Team CPT
 *
 *
 * @category Class
 * @package cleenday-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Team
{
	/**
	 * @var string
	 *
	 * Set post type params
	 */
	private $type = 'team';
	private $slug;
	private $name;
	private $singular_name;
	private $plural_name;

	/**
	 * Team constructor.
	 *
	 * When class is instantiated
	 */
	public function __construct()
	{
		// Register the post type
		$this->name = __( 'Team', 'cleenday-core' );
		$this->singular_name = __( 'Member', 'cleenday-core' );
		$this->plural_name = __( 'Members', 'cleenday-core' );

		$this->slug = Cleenday_Theme_Helper::get_option('team_slug') != '' ? Cleenday_Theme_Helper::get_option('team_slug') : 'team';
		add_action('init', [$this, 'register']);
		add_action('init', [$this, 'register_taxonomy']);
		// Register template
		add_filter('single_template', [$this, 'get_custom_pt_single_template']);
		add_filter('archive_template', [$this, 'get_custom_pt_archive_template']);
	}

	/**
	 * Register post type
	 */
	public function register()
	{

		$team_archive = (bool)Cleenday_Theme_Helper::get_option('team_archives');
		$team_singular = (bool)Cleenday_Theme_Helper::get_option('team_singular');

		$labels = [
			'name' => $this->name,
			'singular_name' => $this->singular_name,
			'add_new' => sprintf( __('Add New %s', 'cleenday-core' ), $this->singular_name ),
			'add_new_item' => sprintf( __('Add New %s', 'cleenday-core' ), $this->singular_name ),
			'edit_item' => sprintf( __('Edit %s', 'cleenday-core'), $this->singular_name ),
			'new_item' => sprintf( __('New %s', 'cleenday-core'), $this->singular_name ),
			'all_items' => sprintf( __('All %s', 'cleenday-core'), $this->plural_name ),
			'view_item' => sprintf( __('View %s', 'cleenday-core'), $this->name ),
			'search_items' => sprintf( __('Search %s', 'cleenday-core'), $this->name ),
			'not_found' => sprintf( __('No %s found' , 'cleenday-core'), strtolower($this->name) ),
			'not_found_in_trash' => sprintf( __('No %s found in Trash', 'cleenday-core'), strtolower($this->name) ),
			'parent_item_colon'  => '',
			'menu_name' => $this->name
		];
		$args = [
			'labels' => $labels,
			'public' => $team_singular,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => ['slug' => $this->slug],
			'capability_type' => 'post',
			'has_archive' => $team_archive,
			'menu_position' => 14,
			'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
			'menu_icon' => 'dashicons-groups',
		];

		register_post_type( $this->type, $args );
	}

	public function register_taxonomy()
	{
		$category = 'category'; // Second part of taxonomy name

		$labels = [
			'name' => sprintf( __( '%s Categories', 'cleenday-core' ), $this->name ),
			'menu_name' => sprintf( __( '%s Categories', 'cleenday-core' ), $this->name ),
			'singular_name' => sprintf( __( '%s Category', 'cleenday-core' ), $this->name ),
			'search_items' =>  sprintf( __( 'Search %s Categories', 'cleenday-core' ), $this->name ),
			'all_items' => sprintf( __( 'All %s Categories', 'cleenday-core' ), $this->name ),
			'parent_item' => sprintf( __( 'Parent %s Category', 'cleenday-core' ), $this->name ),
			'parent_item_colon' => sprintf( __( 'Parent %s Category:', 'cleenday-core' ), $this->name ),
			'new_item_name' => sprintf( __( 'New %s Category Name', 'cleenday-core' ), $this->name ),
			'add_new_item' => sprintf( __( 'Add New %s Category', 'cleenday-core' ), $this->name ),
			'edit_item' => sprintf( __( 'Edit %s Category', 'cleenday-core' ), $this->name ),
			'update_item' => sprintf( __( 'Update %s Category', 'cleenday-core' ), $this->name ),
		];
		$args = [
			'labels' => $labels,
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => ['slug' => $this->slug.'-'.$category],
		];

		register_taxonomy($this->type.'_'.$category, [$this->type], $args);
	}

	// https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
	function get_custom_pt_single_template($single_template)
	{
		global $post;

		if ($post->post_type == $this->type) {
			if (file_exists(get_template_directory().'/single-team.php')) return $single_template;

			$single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'team/templates/single-team.php';
		}

		return $single_template;
	}

	// https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
	function get_custom_pt_archive_template( $archive_template)
	{
		global $post;

		if (is_post_type_archive( $this->type )) {
			if (file_exists(get_template_directory().'/archive-team.php')) return $archive_template;

			$archive_template = plugin_dir_path( dirname( __FILE__ ) ) .'team/templates/archive-team.php';
		}

		return $archive_template;
	}
}
