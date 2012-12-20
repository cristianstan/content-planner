<?php
/*
Plugin Name: Content Planner
Version: 1.0
Description: Create a real-time sitemap.
Author: Sarah Lewis @ WP Moxie
Author URI: http://wpmoxie.com
Plugin URI: http://wpmoxie.com/plugins/content-planner/
*/

/**
 * Enqueue scripts and styles
 */
function wpm_cp_scripts() {
	wp_enqueue_style( 'style', plugins_url( 'css/style.css', __FILE__ ), array(), '20121025'.date('s') );
	wp_enqueue_style( 'badger', plugins_url( 'css/badger.css', __FILE__ ) );

	wp_enqueue_script( 'badger', plugins_url( 'js/badger.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'theme-functions', plugins_url( 'js/functions.js', __FILE__ ), array( 'badger' ), '20121025', true );
	
}
add_action( 'wp_enqueue_scripts', 'wpm_cp_scripts' );


/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function wpm_cp_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_wpm_cp_';

	$meta_boxes[] = array(
		'id'         => 'content_prep',
		'title'      => 'Content Questions',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'What kind of information will go on this page?',
				'desc' => "Just a quick brain-dump&mdash;this isn't shown anywhere but here.",
				'id'   => $prefix . 'content_summary',
				'type' => 'textarea_small',
			),
			array(
				'name' => "What's the main action you want a visitor to take after viewing this page?",
				'id'   => $prefix . 'primary_goal',
				'type' => 'text',
			),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'wpm_cp_metaboxes' );


/**
 * Initialize the metabox class.
 */
function wpm_cp_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'classes/cmb/init.php';
}
add_action( 'init', 'wpm_cp_initialize_cmb_meta_boxes', 9999 );


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/classes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'wpm_cp_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function wpm_cp_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name' 		=> 'Front-end Editor',
			'slug' 		=> 'front-end-editor',
			'required' 	=> true,
		),

	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'wpm_cp';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'plugins.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'plugins.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}


function wpm_cp_big_page_list() {
	if ( ! function_exists( '_fee_init' ) ) {
		echo '<h2>This tool requires the <a href="http://wordpress.org/extend/plugins/front-end-editor/">Front-End Editor</a> plugin to work properly.</h2>';
	} else {
		global $post;
		global $wpm_cp_options;
		$wpm_cp_options = get_option( 'wpm_cp' );

		$args = array(
			'child_of'  => 0,
			'numberposts'  => -1,
			'exclude' => $post->ID,
			'sort_column'      => 'menu_order post_title',
			'sort_order'        => 'ASC',
			'post_type'    => 'page',
		    'post_status'  => 'draft,publish,private,pending'
		);

		$parent_ids = array( 0 );
		$current_depth = 1;


		$output = '';
		$pagelist = get_pages( $args );
		foreach ( $pagelist as $page ) {
			$last = $parent_ids[ count( $parent_ids )-1 ];

			// If the current page parent isn't in the array, start a new child-list
			if ( ! in_array( $page->post_parent, $parent_ids )) {
				$output .= "\r\n".'<ul>'."\r\n";
				$current_depth++;
			} elseif ( $page->post_parent == $last ) {
				// If the current page parent is the last element in the array, this is part of the existing child-list
				$output .= '</li>'."\r\n";
			} else {
				// If the current page parent is in the array but isn't the last element, end the child-list and resume the previous child list	
				$output .= '</li>'."\r\n".'</ul>'."\r\n";
				$current_depth--;
			}
			$output .= '<li>'.wpm_cp_page_item( $page );

			$parent_ids[] = $page->post_parent;
		}
		$output .= '</li>'."\r\n";
		

		$output = substr( $output, 5 );

		for ( $i = $current_depth; $i > 0 ; $i-- ) { 
			$output .= '</ul>'."\r\n";
		}

		$output = '<ul class="content-planner-list">'."\r\n".$output;

		return $output;
	}
}
add_shortcode( 'content-planner', 'wpm_cp_big_page_list' );

function wpm_cp_page_item( $page ) {
	global $wpm_cp_options;

	$issue_count = 0;

	$summary = get_editable_post_meta( $page->ID, '_wpm_cp_content_summary', 'input', true );
	$goal = get_editable_post_meta( $page->ID, '_wpm_cp_primary_goal', 'input', true );

	if ( strpos( $summary, '[empty]' ) ) {
		$issue_count++;
	}
	if ( strpos( $goal, '[empty]' ) ) {
		$issue_count++;
	}
	
	$content_status_cta = '<a href="'.admin_url( 'post.php?post='.$page->ID.'&action=edit' ).'">Work on the content</a>';
	$featured_image_status_cta = '<a href="'.admin_url( 'post.php?post='.$page->ID.'&action=edit' ).'#postimagediv">Add a featured image</a>';
	
	
	$content_status = 'publish' == $page->post_status ? 'The content is complete.' : false ;
	$featured_image_status = has_post_thumbnail( $page->ID ) ? 'the featured image is set.' : false;

	if ( '1' == $wpm_cp_options['featured_image'] ) {
		if ( ! $content_status && ! $featured_image_status ) {
			$status_sentence = $content_status_cta.' &middot; '.$featured_image_status_cta;
			$issue_count += 2;
		} elseif ( $content_status && $featured_image_status ) {
			$status_sentence = rtrim( $content_status, '.' ).' and '.$featured_image_status;
		} elseif ( ! $content_status ) {
			$status_sentence = ucfirst( $featured_image_status ).' '.$content_status_cta;
			$issue_count++;
		} elseif ( ! $featured_image_status ) {
			$status_sentence = $content_status.' '.$featured_image_status_cta;
			$issue_count++;
		}
	} else {
		if ( ! $content_status ) {
			$status_sentence = $content_status_cta;
			$issue_count++;
		} else {
			$status_sentence = $content_status;
		}
	}

	
	// Refactor: add options for this wording

	$output = '<span class="post-title">'.$page->post_title.'</span><span class="issue-badge">'.$issue_count.'</span><span class="extended-info">';
	if ( '1' == $wpm_cp_options['features']['page_summary'] ) {
		$output .= 'This page is about '.$summary.'. ';
	}
	if ( '1' == $wpm_cp_options['features']['page_goal'] ) {
		$output .= 'The main goal of this page is for the visitor to '.$goal.'. ';
	}
	$output .= $status_sentence.'</span>';

	return $output;
}


/**
 * Set up the admin options
 */
require_once dirname( __FILE__ ) . '/classes/nhp-options.php';


/**
 * Temporarily disable the Front-end Editor on the_content
 */
function wpm_cp_disable_fee_on_the_content() {
	if ( class_exists( 'FEE_Core' ) ) {
		global $post;

		// Only proceed if our shortcode is present
		if ( false !== strpos( $post->post_content, '[content-planner]' ) ) {
			global $wpm_cp_fee_settings;

			$wpm_cp_fee_settings = $fee_temp_settings = get_option( 'front-end-editor' );
			if ( false === array_search( 'the_content', $fee_temp_settings['disabled'] ) ) {
				$fee_temp_settings['disabled'][] = 'the_content';
				update_option( 'front-end-editor', $fee_temp_settings);

				// set it up to re-set the FEE options
				add_action( 'shutdown', 'wpm_cp_restore_fee_options' );
			}
		}
	}
}
add_action( 'wp', 'wpm_cp_disable_fee_on_the_content' );

/**
 * Restore the Front-end Editor options
 */
function wpm_cp_restore_fee_options() {
	global $wpm_cp_fee_settings;

	update_option( 'front-end-editor', $wpm_cp_fee_settings);
}